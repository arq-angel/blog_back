<?php

namespace App\Http\Middleware\API\V1;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPublicToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $returnData = [
            'isSuccess' => false,
            'message' => 'Invalid Token',
        ];

        $token = $request->header('Public-Token');

        // Define the public token
        $publicToken = getPublicToken();

        // Check if the token is present and valid
        if (!$token || $token !== $publicToken) {
            return response()->json($returnData, 401);
        }

        return $next($request);
    }
}
