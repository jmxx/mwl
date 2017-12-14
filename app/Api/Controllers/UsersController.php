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

  protected $tokenHeaderName = 'X-Registration-From-Token';

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

  private function preValidateRequest(Request $request)
  {
    $registrationFromToken = !!$request->header($this->tokenHeaderName);

    if ($registrationFromToken) {
      $data = $this->tokenValidation->validateToken($request->token);

      if (!$data) {
        return response()->json([
          'status'  => 'error',
          'message' => 'Invalid email validation token'
        ], 400)->throwResponse();
      }

      if (Carbon::createFromTimestamp($data->exp)->isPast()) {
        return response()->json([
          'status' => 'error',
          'message' => 'Token expired'
        ], 400)->throwResponse();
      }

      $this->validateEmail($data->email);

      return $data;
    }

    return response()->json([
      'status' => 'error',
      'message' => 'We only accept token registrations'
    ], 400)->throwResponse();
  }

  protected function validateToken(Request $request)
  {
    $data = $this->preValidateRequest($request);

    if (!!$data) {
      return response()->json([
        'status' => 'token_valid',
        'email' => $data->email
      ], 200);
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
    $data = $this->preValidateRequest($request);

    if (!!$data) {
      if ($request->email !== $data->email) {
        return response()->json([
          'status' => 'error',
          'errors' => [
            'email' => [
              'Email does not match the token'
            ]
          ],
        ], 422);
      }

      if ($request->password !== $request->password_confirmation) {
        return response()->json([
          'status' => 'error',
          'errors' => [
            'password_confirmation' => [
              'Password confirmation is not valid'
            ]
          ],
        ], 422);
      }

      User::create([
        // 'name' => 'example',
        // 'username' => 'example',
        'email' => $data->email,
        'password' => bcrypt($request->password),
      ]);

      return response()->json([
        'status' => 'user_registration_success',
        'email' => $data->email,
      ], 200);
    }
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
