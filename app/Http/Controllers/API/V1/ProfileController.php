<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        return response()->json([
            "isSuccess" => true,
            "user" => $user
        ]);
    }
}
