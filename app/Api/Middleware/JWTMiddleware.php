<?php namespace MWL\Api\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class JWTMiddleware extends BaseMiddleware
{

  public $allowRoutes = [
    'api/login',
    'api/register',
  ];

  protected function isAllowedRequest(Request $request) {
    return $request->is(...$this->allowRoutes);
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handle(Request $request, Closure $next, $guard = null)
  {
    /**
     * Check if request is login
     * @var [type]
     */
    if ($this->isAllowedRequest($request)) {
      return $next($request);
    }

    if (!$token = $request->cookie('jwt_token')) {
      return response()->json([
        'status' => 'error',
        'message' => 'Token is missing'
      ], 400);
    }

    try {
      $user = $this->auth->authenticate($token);
    } catch (TokenExpiredException $e) {

    } catch (TokenInvalidException $e) {

    } catch (JWTException $e) {

    }

    if (! $user) {
      return response()->json([
        'status' => 'error',
        'message' => 'User not found'
      ]);
    }


    return $next($request);
  }
}
