<?php 
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService {
    public function login(array $data) {
        $user = User::where('email', $data['email'])->first();
        
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid Credentials'
            ];
        }

        $token =  $user->createToken('api-token')->plainTextToken;

        return[
            'success' => true,
            'token' => $token,
            'user' => $user
        ];
    }

    public function logout($user, $currentToken=null) {
        if ($currentToken) {
            $user->currentAccessToken()->delete();
        } else {
            $user->tokens()->delete();
        }

        return [
            'success' => true,
            'message' => 'Logged out successfully',
        ];
    }
}
?>