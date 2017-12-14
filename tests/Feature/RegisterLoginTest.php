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
      ->json('POST', '/api/users/validate', [
        'token' => self::$user['token'],
      ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'status' => 'token_valid',
        'email' => self::$user['email']
      ]);

  }

  /**
   * @depends test_user_registration_200
   */
  public function test_validate_email_token_without_header()
  {
    $response = $this->json('POST', '/api/users/validate', [
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
      ])->json('POST', '/api/users/validate', [
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
   * @depends test_validate_email_token_success
   */
  public function test_register_user_password_unconfirmed()
  {
    $response = $this->withHeaders([
        'X-Registration-From-Token' => true
      ])
      ->json('POST', '/api/users', [
        'token' => self::$user['token'],
        'email' => self::$user['email'],
        'password' => self::$user['password'],
        'password_confirmation' => self::$user['password'] . '123',
      ]);

    $response
      ->assertStatus(422)
      ->assertJson([
        'status' => 'error',
        'errors' => [
          'password_confirmation' => [
            'Password confirmation is not valid'
          ]
        ]
      ]);

    $this->assertDatabaseMissing('users', [
      'email' => self::$user['email']
    ]);
  }

  /**
   * @depends test_validate_email_token_success
   */
  public function test_register_user_email_token_unmatched()
  {
    $response = $this->withHeaders([
        'X-Registration-From-Token' => true
      ])
      ->json('POST', '/api/users', [
        'token' => self::$user['token'],
        'email' => 'another@email.com',
        'password' => self::$user['password'],
        'password_confirmation' => self::$user['password'],
      ]);

    $response
      ->assertStatus(422)
      ->assertJson([
        'status' => 'error',
        'errors' => [
          'email' => [
            'Email does not match the token'
          ]
        ]
      ]);

    $this->assertDatabaseMissing('users', [
      'email' => self::$user['email']
    ]);
  }

  /**
   * @depends test_validate_email_token_success
   */
  public function test_register_user_success()
  {
    $response = $this->withHeaders([
        'X-Registration-From-Token' => true
      ])
      ->json('POST', '/api/users', [
        'token' => self::$user['token'],
        'email' => self::$user['email'],
        'password' => self::$user['password'],
        'password_confirmation' => self::$user['password'],
      ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'status' => 'user_registration_success',
        'email' => self::$user['email']
      ]);

    $this->assertDatabaseHas('users', [
      'email' => self::$user['email']
    ]);

    $this->assertDatabaseMissing('users', [
      'email' => self::$user['email'],
      'password' => self::$user['password']
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

  /**
   * @depends test_register_user_success
   */
  public function test_user_login_unauthorized()
  {
    $response = $this->json('POST', '/api/login', [
      'username' => self::$user['email'],
      'password' => 'wrong_password'
    ]);

    $response
      ->assertStatus(401)
      ->assertJson([
        'status' => 'error'
      ])
      ->assertCookieMissing('jwt_token');
  }

  /**
   * @depends test_register_user_success
   */
  public function test_user_login()
  {
    $response = $this->json('POST', '/api/login', [
      'username' => self::$user['email'],
      'password' => self::$user['password']
    ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'status' => 'ok',
      ])
      ->assertCookie('jwt_token');
  }

  public function tearDown()
  {
    if ('test_user_login' === $this->getName()) {
      User::where('email', self::$user['email'])->delete();
    }
  }
}
