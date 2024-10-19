<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials()
    {
        // create a user with factory
        User::factory()->create([
            'email' => 'raf@dev.com',
            'password' => bcrypt('password'),
        ]);

        // send json post request to login with correct credentials
        $response = $this->postJson('/api/login', [
            'email' => 'raf@dev.com',
            'password' => 'password',
        ]);

        // assert that the response is a 200 with a json structure containing an access token and a token type
        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        // create a user with factory
        User::factory()->create([
            'email' => 'raf@dev.com',
            'password' => bcrypt('password'),
        ]);

        // send json post request to login with incorrect credentials
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // assert that the response is a 401 with a json structure containing an message
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'The provided credentials are incorrect.'
            ]);
    }

    public function test_login_validation_fails_with_missing_fields()
    {
        // send json post request to login with empty array
        $response = $this->postJson('/api/login', []);

        // assert that the response is a 422 with json validation errors for the email and password
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
