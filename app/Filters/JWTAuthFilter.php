<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\AuthService;
use Exception;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (empty($authHeader)) {
            return service('response')
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON([
                    'success' => false,
                    'message' => 'Token Authorization tidak ditemukan.'
                ]);
        }

        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (!$token) {
            return service('response')
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON([
                    'success' => false,
                    'message' => 'Token Bearer tidak valid.'
                ]);
        }

        try {
            $authService = new AuthService();
            $userData = $authService->verifyToken($token);
            
            // Attach user data to request object dynamically
            $request->adminUser = $userData;

            // Check roles if arguments are specified
            if (!empty($arguments)) {
                $userRole = $userData['role'] ?? '';
                // Super admin bypasses specific admin restrictions
                if ($userRole !== 'superadmin' && !in_array($userRole, $arguments)) {
                    return service('response')
                        ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)
                        ->setJSON([
                            'success' => false,
                            'message' => 'Akses ditolak. Anda tidak memiliki wewenang untuk unit ini.'
                        ]);
                }
            }
        } catch (Exception $e) {
            return service('response')
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
