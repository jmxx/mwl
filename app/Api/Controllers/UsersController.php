<?php namespace MWL\Api\Controllers;

use MWL\User;
use MWL\Api\Controllers\BaseController;
use MWL\Api\Validators\UserValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class UsersController extends BaseController
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {

  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return User
   */
  protected function store(Request $request)
  {
    $valid = $this->validate($request, (new UserValidator)->rules());

    // return [];

    User::create([
        'name' => 'example',
        'username' => 'example',
        'email' => 'example@example.com',
        'password' => bcrypt('password'),
    ]);
  }
}
