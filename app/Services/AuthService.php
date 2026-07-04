<?php

namespace App\Services;

use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Authenticate an admin user and return user info + JWT token
     */
    public function login(string $email, string $password)
    {
        $user = $this->userModel->where('email', $email)->first();
        if (!$user) {
            throw new Exception("Email atau password salah.");
        }

        if (!password_verify($password, $user['password'])) {
            throw new Exception("Email atau password salah.");
        }

        if (isset($user['is_active']) && (int)$user['is_active'] === 0) {
            throw new Exception("Akun Anda dinonaktifkan. Silakan hubungi Super Admin.");
        }

        // Generate JWT Token
        $secret = env('jwt.secret') ?: 'SuperSecretKeySigapApplication2026_JWT_Token_Sign_Key';
        $expire = (int)(env('jwt.expire') ?: 86400);
        
        $issuedAt = time();
        $expireTime = $issuedAt + $expire;

        $payload = [
            'iss'  => 'sigap-api',
            'aud'  => 'sigap-client',
            'iat'  => $issuedAt,
            'nbf'  => $issuedAt,
            'exp'  => $expireTime,
            'data' => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'],
                'location_id' => $user['location_id'],
                'service_unit_id' => $user['service_unit_id']
            ]
        ];

        $token = JWT::encode($payload, $secret, 'HS256');

        return [
            'token' => $token,
            'user'  => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'],
                'location_id' => $user['location_id'],
                'service_unit_id' => $user['service_unit_id']
            ],
            'expires_in' => $expire
        ];
    }

    /**
     * Verify a JWT token and return the payload data
     */
    public function verifyToken(string $token)
    {
        try {
            $secret = env('jwt.secret') ?: 'SuperSecretKeySigapApplication2026_JWT_Token_Sign_Key';
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return (array)$decoded->data;
        } catch (Exception $e) {
            throw new Exception("Token tidak valid atau kedaluwarsa: " . $e->getMessage());
        }
    }
}
