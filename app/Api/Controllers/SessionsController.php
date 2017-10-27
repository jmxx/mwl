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

  public function index()
  {
    return [
      'status' => 'ok',
      'user' => $this->auth->user()
    ];
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return User
   */
  protected function store(Request $request)
  {
    $username = $request->input('username');
    $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $request->merge([$field => $username]);

    $credentials = $request->only($field, 'password');

    return $this->requestToken($credentials);
  }

  protected function destroy(Request $request)
  {
    $this->auth->logout();

    // $request->session()->invalidate();

    return [
      'status' => 'ok'
    ];
  }

  protected function requestToken(array $credentials)
  {
    try {
      if (! $token = $this->auth->attempt($credentials)) {
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
