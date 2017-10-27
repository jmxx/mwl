<?php namespace MWL\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Parser\Cookies as JWTCookies;

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
    $user = false;

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
      $this->auth->parser()->setChain([ (new JWTCookies)->setKey('jwt_token') ]);
      $user = $this->auth->parseToken()->authenticate( /*$token*/ );
    } catch (TokenExpiredException $e) {

    } catch (TokenInvalidException $e) {

    } catch (JWTException $e) {

    }

    if (! $user) {
      return response()->json([
        'status' => 'error',
        'message' => 'User not found'
      ], 404);
    }


    return $next($request);
  }
}
