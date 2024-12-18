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
        $user1 = User::factory()->create(['name' => 'Usuário 1']);
        $user2 = User::factory()->create(['name' => 'Usuário 2']);

        $task = Task::factory()->create();

        $task->users()->attach([$user1->id, $user2->id]);

        $task->load('users');

        $this->assertTrue($task->users->contains($user1));
        $this->assertTrue($task->users->contains($user2));

        $this->assertTrue($user1->tasks->contains($task));
        $this->assertTrue($user2->tasks->contains($task));
    }

}
