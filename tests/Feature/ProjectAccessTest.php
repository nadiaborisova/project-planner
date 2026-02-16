<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectAccessTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_user_cannot_access_others_projects() {
        $user = User::factory()->create();
        $otherProject = Project::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/projects/{$otherProject->id}/stats")
            ->assertStatus(403);
    }
}
