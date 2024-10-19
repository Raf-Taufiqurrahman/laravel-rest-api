<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        // send json post request to register a new user
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@dev.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // assert that the response is a 200 with a json structure containing an access token and a token type
        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);

        // assert that the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'test@dev.com',
        ]);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        // create a user with factory
        User::factory()->create(['email' => 'test@dev.com']);

        // send json post request to register a new user with an existing email
        $response = $this->postJson('/api/register', [
            'name' => 'Another User',
            'email' => 'test@dev.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // assert that the response is a 422 with json validation errors for the email
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        // send json post request to register a new user with invalid type data
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        // assert that the response is a 422 with json validation errors for the name, email, and password
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}
