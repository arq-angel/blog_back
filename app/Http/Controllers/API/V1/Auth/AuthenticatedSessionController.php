<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $returnData = ["isSuccess" => false,];
        $validated = $request->validated();

        try {
            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                $returnData["message"] = "Could not login";
                $returnData["error"] = [
                    "email" => "Incorrect email or password",
                ];
            }

            $thoken = $user->createToken('auth_token', ['*'], now()->addWeek())->plainTextToken;
            $returnData = [
                "isSuccess" => true,
                "message" => "Logged in successfully",
                "auth_token" => $thoken,
            ];
        } catch (\Exception $e) {
            if (isDebug()) {
                $returnData["Debug"] = $e->getMessage();
            }
        } catch (\Throwable $th) {
            if (isDebug()) {
                $returnData["Debug"] = $th->getMessage();
            }
        }

        return response()->json($returnData);
    }

    /**
     * Log the user out (invalidate the token).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $returnData = [
            "isSuccess" => false,
            "message" => "User not authenticated",
        ];
        $statusCode = 401;
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            // Revoke the token that was used to authenticate the current request
            $user->currentAccessToken()->delete();

            $returnData = [
                "isSuccess" => true,
                "message" => "Logged out successfully",
            ];
            $statusCode = 200;
        }

        return response()->json($returnData, $statusCode);
    }
}
