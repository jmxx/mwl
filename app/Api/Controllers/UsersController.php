<?php namespace MWL\Api\Controllers;

use \MWL\User;
use \MWL\Api\Controllers\BaseController;
use \MWL\Api\Validators\UserValidator;
use \MWL\Auth\EmailTokenValidation;
use \MWL\Mail\UserPreregisterValidation;
use \Illuminate\Support\Carbon;
use \Illuminate\Support\Facades\Mail;
use \Illuminate\Support\Facades\Validator;
use \Illuminate\Foundation\Auth\RegistersUsers;
use \Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;

class UsersController extends BaseController
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(EmailTokenValidation $tokenValidation)
  {
    $this->tokenValidation = $tokenValidation;
  }

  private function validateEmail($email, $unique = true) {
    $rules = 'required|string|email|max:255' . ( $unique ? '|unique:users' : '');

    $validator = \Validator::make(['email' => $email], [
      'email' => $rules
    ]);

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return User
   */
  protected function store(Request $request)
  {
    $registrationFromToken = !!$request->header('X-Registration-From-Token');

    if ($registrationFromToken) {
      $data = $this->tokenValidation->validateToken($request->token);

      if (!$data) {
        return response()->json([
          'status'  => 'error',
          'message' => 'Invalid email validation token'
        ], 400);
      }

      if (Carbon::createFromTimestamp($data->exp)->isPast()) {
        return response()->json([
          'status' => 'error',
          'message' => 'Token expired'
        ], 400);
      }

      $this->validateEmail($data->email);

      User::create([
        // 'name' => 'example',
        // 'username' => 'example',
        'email' => $data->email,
        'password' => bcrypt($data->email),
      ]);

      return response()->json([
        'status' => 'ok'
      ], 200);
    }

    return response()->json([
      'status' => 'error',
      'message' => 'We only accept token registrations'
    ], 400);
  }

  protected function register(Request $request)
  {
    // TODO: Validate email
    $this->validateEmail($request->email);

    $token = $this->tokenValidation->generateToken($request->email);

    Mail::to($request->email)->send(new UserPreregisterValidation($token));

    return response()->json([
      'status' => 'ok'
    ], 200);
  }

  protected function index(Request $request)
  {
  }
}
