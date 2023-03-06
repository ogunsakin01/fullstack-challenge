<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $user = User::all();
        return response()->json([
            'success' => true,
            'message' => 'User data returned',
            'data' => $user->toArray()
        ]);
    }
}
