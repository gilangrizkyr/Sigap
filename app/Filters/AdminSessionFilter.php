<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminSessionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/admin/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = $session->get('role');

        if (!empty($arguments)) {
            // Super Admin can access everything
            if ($userRole !== 'superadmin' && !in_array($userRole, $arguments)) {
                return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
