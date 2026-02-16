<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskLifecycleTest extends TestCase
{
    use RefreshDatabase; 

    #[Test]
    public function test_can_create_task_and_then_soft_delete_it() 
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $user->teams()->attach($project->team_id, ['role' => 'admin']);

        $response = $this->actingAs($user)->postJson("/api/tasks", [
            'project_id' => $project->id,
            'title'      => 'New Professional Task',
            'status'     => 'todo',
            'priority'   => 'medium'
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'New Professional Task');

        $taskId = $response->json('data.id');
        
        $this->actingAs($user)
             ->deleteJson("/api/tasks/{$taskId}")
             ->assertStatus(204);

        $this->assertSoftDeleted('tasks', ['id' => $taskId]);
    }
}