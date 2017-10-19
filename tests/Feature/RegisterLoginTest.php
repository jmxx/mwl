<?php namespace Tests\Feature;

use MWL\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterLoginTest extends TestCase
{  
  public $user = [
    'email' => 'test@test.dev',
    'password' => '123456'
  ];

  public function test_user_registration_200()
  {
    $response = $this->json('POST', '/api/register', [
      'email' => $this->user['email'],
      'password' => $this->user['password'],
      'password_confirmation' => $this->user['password'],
    ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'status' => 'ok'
      ]);

    $this->assertDatabaseHas('users', [
      'email' => $this->user['email']
    ]);
  }

  public function test_user_registration_duplicated()
  {
    $response = $this->json('POST', '/api/register', [
      'email' => $this->user['email'],
      'password' => $this->user['password'],
      'password_confirmation' => $this->user['password'],
    ]);

    $response
      ->assertStatus(422)
      ->assertJson([
        'errors' => [
          'email' => [
            'The email has already been taken.'
          ]
        ]
      ]);

    $this->assertDatabaseHas('users', [
      'email' => $this->user['email']
    ]);
  }

  public function tearDown()
  {
    if ('test_user_registration_duplicated' === $this->getName()) {
      User::where('email', $this->user['email'])->delete();
    }
  }
}
