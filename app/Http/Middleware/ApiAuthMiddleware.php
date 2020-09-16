<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuthMiddleware
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
        //Comprobar si el usuario esta identificado
     
        $hash = $request->header("Authorization", null);
        $jwtAut = new \JwtAuth();
        $checkTo = $jwtAut->checktoken($hash);
        if ($checkTo) {
            return $next($request);
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'El usuario no esta Autenticado',
            );
            return response()->json($data);
        }
    }
}
