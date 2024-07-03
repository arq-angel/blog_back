<?php

namespace App\Http\Middleware\API\V1;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $returnData = [
            "isSuccess" => false,
            "message" => "Invalid Token. Please login again.",
        ];

        try {
            $token = $request->bearerToken();
            if ($token) {
                // Use the findToken method to find the token securely which prevents sql injection
                $personalAccessToken = PersonalAccessToken::findToken($token);

                // checking that the token doesn't exist or is already expired
                if (!$personalAccessToken || ($personalAccessToken->expires_at && $personalAccessToken->expires_at->isPast(
                        ))) {
                    return response()->json($returnData);
                }
                return $next($request);
            }
        } catch (\Exception $e) {
            if (isDebug()) {
                $returnData["Debug"] = $e->getMessage();
            }
        } catch (\Throwable $th) {
            if (isDebug()) {
                $returnData["Debug"] = $th->getMessage();
            }
        }
        return response()->json($returnData, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
