<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Traits\HasWalletHelpers;

class TokenController extends Controller
{
    // Issue new access token + refresh token
    public function getToken(Request $request)
    {
        $userId = $request->input('userId');
        if (!$userId) {
            return response()->json(['error' => 'userId required'], 400);
        }

        return $this->getToken($userId);
    }

    // Refresh access token using refresh token
    public function refreshToken(Request $request)
    {
        $refreshToken = $request->input('refresh_token');
        if (!$refreshToken) {
            return response()->json(['error' => 'refresh_token required'], 400);
        }

        try {
            return $this->refreshToken($refreshToken);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }
    }

}
