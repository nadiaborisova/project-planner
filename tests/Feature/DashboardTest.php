<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_calculates_correct_completion_percentage()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $user->teams()->attach($project->team_id, ['role' => 'admin']);

        Task::factory()->create(['project_id' => $project->id, 'status' => 'done']);
        Task::factory()->create(['project_id' => $project->id, 'status' => 'todo']);

        $response = $this->actingAs($user)
                         ->getJson("/api/projects/{$project->id}/stats");

        $response->assertStatus(200)
                 ->assertJsonPath('stats.completion_rate', '50%')
                 ->assertJsonPath('stats.total_tasks', 2);
    }
}
