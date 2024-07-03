<?php

namespace App\Http\Middleware\API\V1;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
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
            "message" => "Email is not verified."
        ];
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $returnData['message'] = "Validation error.";
            $returnData['errors'] = $validator->errors();
            return response()->json($returnData, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $validated = $validator->validated();
            $user = User::where('email', $validated['email'])->first();
            // ensuring that the user exists and email is verifiable and has verified the email
            if ($user && ($user instanceof MustVerifyEmail) && ($user->hasVerifiedEmail())) {
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
