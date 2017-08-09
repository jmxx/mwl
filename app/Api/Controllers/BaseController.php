<?php namespace MWL\Api\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  protected function formatValidationErrors(Validator $validator)
  {
    return [
      'errors' => $validator->errors()->getMessages(),
      'status' => 'error'
    ];
  }
}
