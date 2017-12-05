<?php namespace Tests\Feature;

use \MWL\User;
use \MWL\Mail\UserPreregisterValidation;
use \MWL\Auth\EmailTokenValidation;
use \Tests\TestCase;
use \Illuminate\Support\Facades\Mail;
use \Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterLoginTest extends TestCase
{
  public static $tokenValidation;

  public static $user = [
    'email' => 'test@test.dev',
    'password' => '123456'
  ];

  public static function setUpBeforeClass() {
    self::$tokenValidation = new EmailTokenValidation;
  }

  public function test_user_registration_200()
  {
    Mail::fake();

    $response = $this->json('POST', '/api/users/register', [
      'email' => self::$user['email'],
    ]);

    Mail::assertSent(UserPreregisterValidation::class, function ($mail) {
      self::$user['token'] = $mail->token;
      return $mail->hasTo(self::$user['email']) && is_string($mail->token);
    });

    $response
      ->assertStatus(200)
      ->assertJson([
        'status' => 'ok'
      ]);
  }

  /**
   * @depends test_user_registration_200
   */
  public function test_validate_email_token_success()
  {
    $response = $this->withHeaders([
        'X-Registration-From-Token' => true
      ])
      ->json('POST', '/api/users', [
        'token' => self::$user['token'],
      ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'status' => 'ok'
      ]);

    $this->assertDatabaseHas('users', [
      'email' => self::$user['email']
    ]);
  }

  /**
   * @depends test_user_registration_200
   */
  public function test_validate_email_token_without_header()
  {
    $response = $this->json('POST', '/api/users', [
        'token' => self::$user['token'],
      ]);

    $response
      ->assertStatus(400)
      ->assertJson([
        'status' => 'error'
      ]);
  }

  /**
   * @depends test_user_registration_200
   */
  public function test_validate_email_token_invalid()
  {
    $response = $this->withHeaders([
        'X-Registration-From-Token' => true
      ])->json('POST', '/api/users', [
        'token' => self::$user['token'] . 'invalid',
      ]);

    $response
      ->assertStatus(400)
      ->assertJson([
        'status' => 'error',
        'message' => 'Invalid email validation token'
      ]);
  }

  /**
   * @depends test_user_registration_200
   */
  public function test_user_registration_duplicated()
  {
    Mail::fake();

    $response = $this->json('POST', '/api/users/register', [
      'email' => self::$user['email'],
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
      'email' => self::$user['email']
    ]);
  }

  public function test_validate_email_token_expiration()
  {
    // Generate token
    $token = self::$tokenValidation->generateToken(self::$user['email'], -1);

    $response = $this->withHeaders([
        'X-Registration-From-Token' => true
      ])
      ->json('POST', '/api/users', [
        'token' => $token,
      ]);

    $response
      ->assertStatus(400)
      ->assertJson([
        'status' => 'error',
        'message' => 'Token expired'
      ]);
  }

  /**
   * @depends test_user_registration_duplicated
   */
  public function test_validate_email_token_duplicated()
  {
    $response = $this->withHeaders([
        'X-Registration-From-Token' => true
      ])
      ->json('POST', '/api/users', [
        'token' => self::$user['token'],
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
      'email' => self::$user['email']
    ]);
  }

  public function tearDown()
  {
    if ('test_validate_email_token_duplicated' === $this->getName()) {
      User::where('email', self::$user['email'])->delete();
    }
  }
}
