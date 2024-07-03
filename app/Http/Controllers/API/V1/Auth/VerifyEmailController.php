<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(User $id, Request $request): JsonResponse
    {
        $user = $id;
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                "isSuccess" => false,
                "isVerified" => true,
                'message' => 'Email address already verified.'
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
            $user->is_active = 1;
            $user->save();
        }

        return response()->json([
            "isSuccess" => true,
            "isVerified" => true,
            "message" => "Email address successfully verified."
        ]);
    }
}
