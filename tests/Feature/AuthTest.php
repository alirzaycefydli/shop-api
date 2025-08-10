<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->createUser();

        $response->assertCreated()
            ->assertJsonStructure([
                'success',
                'message',
                'data' =>
                    [
                        'user' => [
                            'id',
                            'name',
                            'email',
                        ],
                        'token',
                    ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    public function test_user_cannot_register_with_invalid_fields()
    {
        $response = $this->postJson('api/v1/auth/register', [
            'name' => 'John Doe',
            'email' => 'john.com', // invalid email
            'password' => 'password',
            'password_confirmation' => 'wrong_password', // mismatch
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = $this->createUser();

        $response = $this->postJson('api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'password',
            'token' => $user['data']['token']
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' =>
                    [
                        'user' => [
                            'id',
                            'name',
                            'email',
                        ],
                        'token',
                    ]
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $this->createUser();

        $response = $this->postJson('api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'wrong_password',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_logout(): void
    {
        $user = $this->createUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['data']['token'],
        ])->postJson('api/v1/auth/logout');

        $response->assertOk();
    }

    private function createUser(): \Illuminate\Testing\TestResponse
    {
        return $this->postJson('api/v1/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    }
}
