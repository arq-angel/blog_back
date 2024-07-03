<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        $returnData = [
            "isSuccess" => false,
        ];
        $request->validated();

        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->string('password')),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);

            event(new Registered($user));

            $returnData = [
                "isSuccess" => true,
                "message" => "User created successfully. Please verify email to log in",
                "user" => $user
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
}
