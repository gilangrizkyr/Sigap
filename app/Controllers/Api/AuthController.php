<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\AuthService;
use CodeIgniter\API\ResponseTrait;
use Exception;

class AuthController extends BaseController
{
    use ResponseTrait;

    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login()
    {
        $json = $this->request->getJSON(true);
        
        $email = $json['email'] ?? $this->request->getPost('email');
        $password = $json['password'] ?? $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return $this->fail('Email dan password wajib diisi.', 400);
        }

        try {
            $result = $this->authService->login($email, $password);
            return $this->respond([
                'success' => true,
                'message' => 'Login berhasil.',
                'data'    => $result
            ], 200);
        } catch (Exception $e) {
            return $this->fail($e->getMessage(), 401);
        }
    }
}
