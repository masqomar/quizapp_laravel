<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                $this->toJson("Token is Invalid");
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                $this->toJson("Token is Expired");
            }else{
                $this->toJson("Authorization Token not found");
            }
        }
        return $next($request)->header('Access-Control-Allow-Origin', 'Access-Control-Allow-Origin, Accept');
    }

    private function toJson($message)
    {
         return response()->json([
                'success' => false,
                'message' => $message
            ], 401);
    }
}
