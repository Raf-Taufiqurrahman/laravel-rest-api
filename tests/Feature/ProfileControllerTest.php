<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_profile()
    {
        // send json get request to view profile
        $response = $this->getJson('/api/profile');

        // assert that the response is a 401
        $response->assertStatus(401);
    }

    public function test_user_can_view_their_profile()
    {
        // create a user with factory
        $user = User::factory()->create();

        // send json get request to view profile
        $response = $this->actingAs($user)
            ->getJson('/api/profile');

        // assert that the response is a 200 with a json structure containing the user's id, name, and email
        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
    }

    public function test_user_can_update_their_profile()
    {
        // create a user with factory
        $user = User::factory()->create();

        // send json put request to update profile
        $response = $this->actingAs($user)
            ->putJson('/api/profile', [
                'name' => 'Raf',
                'email' => 'raf@example.com'
            ]);

        // assert that the response is a 200 with a json structure containing the updated user's id, name, and email
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Profile updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => 'Raf',
                    'email' => 'raf@example.com',
                ]
            ]);

        // assert that the user was updated in the database
        $this->assertDatabaseHas('users', [
            'name' => 'Raf',
            'email' => 'raf@example.com',
        ]);
    }
}
