<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Morilog\Jalali\Jalalian;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {

                return $this->JsonResponse(null, 'Token is Invalid', 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->JsonResponse(null, 'Token is Invalid', 401);
            } else {
                return $this->JsonResponse(null, 'Authorization Token Not Found', 401);
            }
        }
        return $next($request);
    }
    public function JsonResponse($data, $error, $status = 200)
    {
        return response()->json(
            [
                'data' => null,
                'responseDate' => Jalalian::forge('now')->format('Y/m/d'),
                'responseTime' => Jalalian::forge('now')->format('H:m'),
                'errorMessage' => $error
            ],
            $status
        );
    }
}
