<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    private $user;

    public function setUp():void
    {
        parent::setUp();
        $this->user = [
            'first_name' => 'shehab',
            'last_name' => 'waleed',
            'username' => 'shehab2022',
            'email' => 'shehab@gmail.com',
            'country' => 'Egypt',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    }

    public function test_registration_works_correctly(): void
    {
        $response = $this->post('/api/v1/register', $this->user);

        unset($this->user['password']);
        unset($this->user['password_confirmation']);

        $this->assertDatabaseHas('users', $this->user);
        $response->assertStatus(201);
    }

    public function test_registration_validation(): void
    {
        $response = $this->post('/api/v1/register', [
            'first_name' => $this->user['first_name'],
            'last_name' => $this->user['last_name'],
        ]);

        $response->assertStatus(422);
    }

    public function test_login_failed()
    {
        $response = $this->post('/api/v1/register', $this->user);

        $response = $this->post('api/v1/login', [
            'email' => $this->user['email'],
            'password' => 'wrongPassword',
        ]);

        $response->assertStatus(401);
    }
}
