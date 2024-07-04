<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function isConnected(Request $request): JsonResponse
    {
        return response()->json([
            "isSuccess" => true,
            "message" => "Connection is successful",
        ]);
    }
}
