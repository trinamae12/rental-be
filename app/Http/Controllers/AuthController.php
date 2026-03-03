<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function login (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $result = $this->authService->login($request->only('email', 'password'));

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 401);
        }

        return response()->json([
            'token' => $result['token'],
            'user' => $result['user'],
        ]);
    }

    public function logout (Request $request) {
        $user = $request->user();
        $result = $this->authService->logout($user, $user->currentAccessToken());

        return response()->json($result);
    }
}
