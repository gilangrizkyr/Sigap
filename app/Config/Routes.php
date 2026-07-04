<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ─── Landing / Publik ────────────────────────────────────────────────────────
$routes->get('/', 'Web\HomeController::index');
$routes->get('/sigap', 'Web\HomeController::index');
$routes->get('/tracking', 'Web\HomeController::tracking');

// ─── DPMPTSP Portal (semua route berada dalam scope /dpmptsp) ────────────────
$routes->get('/dpmptsp',          'Web\Dpmptsp\HomeController::index');
$routes->get('/dpmptsp/tracking', 'Web\Dpmptsp\HomeController::tracking');
$routes->get('/dpmptsp/faq',      'Web\Dpmptsp\HomeController::faq');
$routes->get('/dpmptsp/about',    'Web\Dpmptsp\HomeController::about');
$routes->post('/dpmptsp/submit',  'Web\Dpmptsp\HomeController::submit');

// Backward-compat alias
$routes->get('/pengaduan/dpmptsp', 'Web\Dpmptsp\HomeController::index');

// ─── MPP Portal (semua route berada dalam scope /mpp) ────────────────────────
$routes->get('/mpp',          'Web\Mpp\HomeController::index');
$routes->get('/mpp/tracking', 'Web\Mpp\HomeController::tracking');
$routes->get('/mpp/faq',      'Web\Mpp\HomeController::faq');
$routes->get('/mpp/about',    'Web\Mpp\HomeController::about');
$routes->post('/mpp/submit',  'Web\Mpp\HomeController::submit');

// Backward-compat alias
$routes->get('/pengaduan/mpp', 'Web\Mpp\HomeController::index');


// Admin Auth Web Routes
$routes->get('/admin/login', 'Web\AdminController::login');
$routes->post('/admin/login', 'Web\AdminController::attemptLogin');
$routes->get('/admin/logout', 'Web\AdminController::logout');

// Protected Admin Web Routes (Session Filter)
$routes->group('admin', ['filter' => 'admin_session'], function ($routes) {
    $routes->get('dashboard', 'Web\AdminController::dashboard');
    $routes->get('complaints', 'Web\AdminController::complaints');
    $routes->get('complaints/(:num)', 'Web\AdminController::complaintDetail/$1');
    $routes->post('complaints/(:num)/status', 'Web\AdminController::updateComplaintStatus/$1');
    $routes->post('complaints/(:num)/reply', 'Web\AdminController::replyComplaint/$1');
    $routes->get('notifications/unread', 'Web\AdminController::getUnreadNotifications');
    
    // Superadmin Specific Web Routes
    $routes->get('users', 'Web\AdminController::users', ['filter' => 'admin_session:superadmin']);
    $routes->post('users/create', 'Web\AdminController::createUser', ['filter' => 'admin_session:superadmin']);
    $routes->post('users/toggle/(:num)', 'Web\AdminController::toggleUserStatus/$1', ['filter' => 'admin_session:superadmin']);
    $routes->post('users/reset-password/(:num)', 'Web\AdminController::resetUserPassword/$1', ['filter' => 'admin_session:superadmin']);
    $routes->post('users/delete/(:num)', 'Web\AdminController::deleteUser/$1', ['filter' => 'admin_session:superadmin']);
    
    $routes->get('locations', 'Web\AdminController::locations', ['filter' => 'admin_session:superadmin']);
    $routes->post('locations/create', 'Web\AdminController::createLocation', ['filter' => 'admin_session:superadmin']);
    $routes->post('locations/delete/(:num)', 'Web\AdminController::deleteLocation/$1', ['filter' => 'admin_session:superadmin']);
    
    $routes->get('service-units', 'Web\AdminController::serviceUnits', ['filter' => 'admin_session:superadmin']);
    $routes->post('service-units/create', 'Web\AdminController::createServiceUnit', ['filter' => 'admin_session:superadmin']);
    $routes->post('service-units/delete/(:num)', 'Web\AdminController::deleteServiceUnit/$1', ['filter' => 'admin_session:superadmin']);

    $routes->get('categories', 'Web\AdminController::categories', ['filter' => 'admin_session:superadmin']);
    $routes->post('categories/create', 'Web\AdminController::createCategory', ['filter' => 'admin_session:superadmin']);
    $routes->post('categories/delete/(:num)', 'Web\AdminController::deleteCategory/$1', ['filter' => 'admin_session:superadmin']);

    $routes->get('analytics', 'Web\AdminController::analytics', ['filter' => 'admin_session:superadmin']);
    $routes->get('audit-logs', 'Web\AdminController::auditLogs', ['filter' => 'admin_session:superadmin']);
    
    $routes->get('database', 'Web\AdminController::database', ['filter' => 'admin_session:superadmin']);
    $routes->post('database/backup', 'Web\AdminController::backupDatabase', ['filter' => 'admin_session:superadmin']);
    $routes->post('database/restore', 'Web\AdminController::restoreDatabase', ['filter' => 'admin_session:superadmin']);
    
    $routes->post('complaints/(:num)/assign', 'Web\AdminController::assignComplaint/$1');
    
    $routes->get('settings', 'Web\AdminController::settings', ['filter' => 'admin_session:superadmin']);
    $routes->post('settings/update', 'Web\AdminController::updateSettings', ['filter' => 'admin_session:superadmin']);
});

// Public API Routes
$routes->post('/api/complaints', 'Api\ComplaintController::create');
$routes->get('/api/complaints/tracking', 'Api\ComplaintController::tracking');
$routes->post('/api/uploads', 'Api\ComplaintController::upload');
$routes->post('/api/auth/login', 'Api\AuthController::login');

// Protected Admin API Routes (JWT Filter)
$routes->group('api/admin', ['filter' => 'jwt'], function ($routes) {
    $routes->get('dashboard', 'Api\ComplaintController::dashboard');
    $routes->get('complaints', 'Api\ComplaintController::index');
    $routes->get('complaints/(:num)', 'Api\ComplaintController::show/$1');
    $routes->put('complaints/(:num)/status', 'Api\ComplaintController::updateStatus/$1');
    $routes->post('complaints/(:num)/reply', 'Api\ComplaintController::reply/$1');
});
