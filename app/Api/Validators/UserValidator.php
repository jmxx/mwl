<?php namespace MWL\Api\Validators;

class UserValidator
{
  public function rules() {
    return [
      'name' => 'required|string|max:255',
      'username' => 'required|string|max:255|unique:users',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ];
  }
}
