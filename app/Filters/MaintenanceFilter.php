<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $settingsPath = WRITEPATH . 'settings.json';
        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true);
            if (!empty($settings['maintenance_mode'])) {
                // If it is an API request, return JSON
                if (strpos($request->getUri()->getPath(), 'api/') !== false) {
                    return service('response')
                        ->setStatusCode(503)
                        ->setJSON([
                            'success' => false,
                            'message' => 'Sistem sedang dalam pemeliharaan (Maintenance Mode). Silakan coba lagi beberapa saat lagi.'
                        ]);
                }
                
                // Otherwise render a maintenance view
                return service('response')
                    ->setStatusCode(503)
                    ->setBody(view('maintenance'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
