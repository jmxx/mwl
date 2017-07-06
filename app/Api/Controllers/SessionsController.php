<?php namespace MWL\Api\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Tymon\JWTAuth\Facades\JWTAuth;

use MWL\User;
use MWL\Api\Controllers\BaseController;
use MWL\Api\Validators\UserValidator;

class SessionsController extends BaseController
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(Guard $auth)
  {
    $this->auth = $auth;
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return User
   */
  protected function store(Request $request)
  {
    $credentials = $request->only('email', 'password');

    return $this->requestToken($credentials);
  }

  protected function requestToken(array $credentials)
  {
    try {
      if (! $token = JWTAuth::attempt($credentials)) {
        return response()->json([
          'status'  => 'error',
          'message' => 'Invalid credentials'
        ], 401);
      }
    } catch (JWTException $e) {
      return response()->json([
        'status'  => 'error',
        'message' => 'Could not create token'
      ], 500);
    }

    return response()->json([
      'status' => 'success'
    ])->cookie('jwt_token', $token, 120, null, null, false, true);
  }
}
