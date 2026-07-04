<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Services\AuthService;
use App\Repositories\ComplaintRepository;
use App\Models\UserModel;
use App\Models\LocationModel;
use App\Models\ServiceUnitModel;
use App\Models\ComplaintCategoryModel;
use Exception;

class AdminController extends BaseController
{
    protected $authService;
    protected $repository;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->repository = new ComplaintRepository();
    }

    /**
     * GET /admin/login
     */
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }

    /**
     * POST /admin/login
     */
    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Email dan password wajib diisi.');
        }

        try {
            $result = $this->authService->login($email, $password);
            
            // Set session variables for Web Auth
            $session = session();
            $session->set([
                'user_id'         => $result['user']['id'],
                'name'            => $result['user']['name'],
                'email'           => $result['user']['email'],
                'role'            => $result['user']['role'],
                'location_id'     => $result['user']['location_id'],
                'service_unit_id' => $result['user']['service_unit_id'],
                'jwt_token'       => $result['token'], // Store JWT to allow AJAX calls
                'logged_in'       => true
            ]);

            return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang kembali, ' . $result['user']['name'] . '!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * GET /admin/logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * GET /admin/dashboard
     */
    public function dashboard()
    {
        $role = session()->get('role');
        $locationId = $this->getLocationIdFromRole($role);
        $serviceUnitId = session()->get('service_unit_id');

        if ($role === 'superadmin') {
            $summary = $this->repository->getAnalyticsSummary();
            $summary['total_dpmptsp'] = $this->repository->getComplaintsCount(['location_id' => 1]);
            $summary['total_mpp'] = $this->repository->getComplaintsCount(['location_id' => 2]);
            $summary['active_admins'] = $this->repository->getActiveAdminsCount();
            
            $data['summary'] = $summary;
            $data['monthly_chart'] = $this->repository->getMonthlyChartData();
            $data['category_chart'] = $this->repository->getCategoryChartData();
            $data['location_chart'] = $this->repository->getLocationChartData();
            $data['unit_chart'] = $this->repository->getUnitChartData();
        } else {
            $data['summary'] = $this->repository->getAnalyticsSummary($locationId, $serviceUnitId);
            $data['monthly_chart'] = $this->repository->getMonthlyChartData($locationId, $serviceUnitId);
            $data['category_chart'] = $this->repository->getCategoryChartData($locationId, $serviceUnitId);
            $data['unit_chart'] = ($locationId === 1 || $role === 'pic_unit') ? [] : $this->repository->getUnitChartData();
        }

        $data['role'] = $role;
        $data['active_menu'] = 'dashboard';

        return view('admin/dashboard', $data);
    }

    /**
     * GET /admin/complaints
     */
    public function complaints()
    {
        $role = session()->get('role');
        $locationId = $this->getLocationIdFromRole($role);
        $serviceUnitId = session()->get('service_unit_id');

        $status = $this->request->getGet('status');
        $type = $this->request->getGet('complaint_type');
        $search = $this->request->getGet('search');
        $filterLocation = $this->request->getGet('location_id');
        $filterUnit = $this->request->getGet('service_unit_id');

        $filters = [
            'location_id'     => $locationId ?: $filterLocation,
            'service_unit_id' => $role === 'pic_unit' ? $serviceUnitId : $filterUnit,
            'status'          => $status,
            'complaint_type'  => $type,
            'search'          => $search,
            'current_user_id' => session()->get('user_id')
        ];

        // Pagination setup
        $currentPage = $this->request->getGet('page') ?: 1;
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;

        $data['complaints'] = $this->repository->getComplaints($filters, $limit, $offset);
        $total = $this->repository->getComplaintsCount($filters);
        
        $data['total_pages'] = ceil($total / $limit);
        $data['current_page'] = $currentPage;
        $data['filters'] = $filters;
        $data['active_menu'] = 'complaints';

        // Set specific submenus
        if ($status === 'submitted') {
            $data['active_menu'] = 'new_complaints';
        } elseif ($status === 'processing') {
            $data['active_menu'] = 'processing_complaints';
        } elseif ($status === 'resolved') {
            $data['active_menu'] = 'resolved_complaints';
        }

        return view('admin/complaints', $data);
    }

    /**
     * GET /admin/complaints/{id}
     */
    public function complaintDetail($id)
    {
        $role = session()->get('role');
        $locationId = $this->getLocationIdFromRole($role);
        $serviceUnitId = session()->get('service_unit_id');

        $complaint = $this->repository->getDetails((int)$id);

        if (!$complaint) {
            return redirect()->to('/admin/complaints')->with('error', 'Pengaduan tidak ditemukan.');
        }

        if ($locationId && (int)$complaint['location_id'] !== $locationId) {
            return redirect()->to('/admin/complaints')->with('error', 'Akses ditolak. Pengaduan bukan dari unit Anda.');
        }

        if ($role === 'pic_unit' && (int)$complaint['service_unit_id'] !== (int)$serviceUnitId) {
            return redirect()->to('/admin/complaints')->with('error', 'Akses ditolak. Pengaduan bukan dari sub-unit Anda.');
        }

        // Mark as read for this administrator
        $userId = session()->get('user_id');
        if ($userId) {
            $this->repository->markAsRead((int)$id, (int)$userId);
        }

        $data['complaint'] = $complaint;
        $data['active_menu'] = 'complaints';
        $data['role'] = $role;

        $data['admins'] = [];
        if (in_array($role, ['admin_dpmptsp', 'admin_mpp'])) {
            $userModel = new UserModel();
            $data['admins'] = $userModel->where('is_active', 1)
                                        ->where('location_id', $locationId)
                                        ->where('role !=', 'superadmin')
                                        ->findAll();
        }

        return view('admin/complaint_detail', $data);
    }

    /**
     * POST /admin/complaints/{id}/status
     */
    public function updateComplaintStatus($id)
    {
        $role = session()->get('role');
        $locationId = $this->getLocationIdFromRole($role);
        $serviceUnitId = session()->get('service_unit_id');

        $complaint = $this->repository->findById((int)$id);
        if (!$complaint) {
            return redirect()->back()->with('error', 'Pengaduan tidak ditemukan.');
        }

        // Superadmin cannot change complaint status
        if ($role === 'superadmin') {
            return redirect()->back()->with('error', 'Akses ditolak. Super Admin tidak berwenang mengubah status pengaduan.');
        }

        if ($locationId && (int)$complaint['location_id'] !== $locationId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if ($role === 'pic_unit' && (int)$complaint['service_unit_id'] !== (int)$serviceUnitId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $newStatus = $this->request->getPost('status');
        $allowedStatus = ['submitted', 'verified', 'processing', 'waiting_response', 'resolved', 'rejected'];

        if (!in_array($newStatus, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $adminName = session()->get('name');
        $success = $this->repository->updateStatus((int)$id, $newStatus, $adminName);

        if ($success) {
            return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui status.');
        }
    }

    /**
     * POST /admin/complaints/{id}/reply
     */
    public function replyComplaint($id)
    {
        $role = session()->get('role');
        $locationId = $this->getLocationIdFromRole($role);
        $serviceUnitId = session()->get('service_unit_id');

        $complaint = $this->repository->findById((int)$id);
        if (!$complaint) {
            return redirect()->back()->with('error', 'Pengaduan tidak ditemukan.');
        }

        // Superadmin cannot reply to complaints
        if ($role === 'superadmin') {
            return redirect()->back()->with('error', 'Akses ditolak. Super Admin tidak berwenang membalas pengaduan.');
        }

        if ($locationId && (int)$complaint['location_id'] !== $locationId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if ($role === 'pic_unit' && (int)$complaint['service_unit_id'] !== (int)$serviceUnitId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $message = $this->request->getPost('message');
        if (empty(trim($message))) {
            return redirect()->back()->with('error', 'Balasan tidak boleh kosong.');
        }

        $adminId = session()->get('user_id');
        $adminName = session()->get('name');

        $success = $this->repository->addReply((int)$id, $adminId, $message, $adminName);

        if ($success) {
            // Auto update status to processing if it was submitted/verified
            if (in_array($complaint['status'], ['submitted', 'verified'])) {
                $this->repository->updateStatus((int)$id, 'processing', $adminName);
            }
            return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim balasan.');
        }
    }

    /**
     * GET /admin/users (Super Admin only)
     */
    public function users()
    {
        $role = session()->get('role');
        if ($role !== 'superadmin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses khusus Super Admin.');
        }

        $userModel = new UserModel();
        $locationModel = new LocationModel();
        $serviceUnitModel = new ServiceUnitModel();

        $data['users'] = $userModel
            ->select('users.*, locations.name as location_name, service_units.name as service_unit_name')
            ->join('locations', 'locations.id = users.location_id', 'left')
            ->join('service_units', 'service_units.id = users.service_unit_id', 'left')
            ->orderBy('users.id', 'DESC')
            ->findAll();

        $data['locations'] = $locationModel->findAll();
        $data['service_units'] = $serviceUnitModel->findAll();
        $data['active_menu'] = 'users';

        return view('admin/users', $data);
    }

    /**
     * POST /admin/users/create (Super Admin only)
     */
    public function createUser()
    {
        $role = session()->get('role');
        if ($role !== 'superadmin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak.');
        }

        $userModel = new UserModel();
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $userRole = $this->request->getPost('role');
        $locationId = $this->request->getPost('location_id') ?: null;
        $serviceUnitId = $this->request->getPost('service_unit_id') ?: null;

        // Validation overrides based on role rules
        if ($userRole === 'superadmin') {
            $locationId = null;
            $serviceUnitId = null;
        } elseif ($userRole === 'admin_dpmptsp') {
            $locationId = 1;
            $serviceUnitId = null;
        } elseif ($userRole === 'admin_mpp') {
            $locationId = 2;
            $serviceUnitId = null;
        } elseif ($userRole === 'pic_unit') {
            if (empty($serviceUnitId)) {
                return redirect()->back()->withInput()->with('error', 'PIC Unit wajib memilih Service Unit.');
            }
            $suModel = new ServiceUnitModel();
            $unit = $suModel->find($serviceUnitId);
            $locationId = $unit ? $unit['location_id'] : 2;
        }

        $userData = [
            'name'            => $name,
            'email'           => $email,
            'password'        => password_hash($password, PASSWORD_DEFAULT),
            'role'            => $userRole,
            'location_id'     => $locationId,
            'service_unit_id' => $serviceUnitId,
            'is_active'       => 1
        ];

        if (!$userModel->insert($userData)) {
            $errors = implode(', ', $userModel->errors());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan admin: ' . $errors);
        }

        return redirect()->to('/admin/users')->with('success', 'Admin baru berhasil ditambahkan.');
    }

    /**
     * POST /admin/users/toggle/{id} (Super Admin only)
     */
    public function toggleUserStatus($id)
    {
        $role = session()->get('role');
        if ($role !== 'superadmin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Admin tidak ditemukan.');
        }

        if ((int)$user['id'] === (int)session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak bisa menonaktifkan diri sendiri.');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $userModel->update($id, ['is_active' => $newStatus]);

        return redirect()->back()->with('success', 'Status admin berhasil diubah.');
    }

    /**
     * POST /admin/users/reset-password/{id} (Super Admin only)
     */
    public function resetUserPassword($id)
    {
        $role = session()->get('role');
        if ($role !== 'superadmin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak.');
        }

        $newPassword = $this->request->getPost('password');
        if (empty($newPassword) || strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Admin tidak ditemukan.');
        }

        $userModel->update($id, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);

        return redirect()->back()->with('success', 'Password admin berhasil direset.');
    }

    /**
     * POST /admin/users/delete/{id} (Super Admin only)
     */
    public function deleteUser($id)
    {
        $role = session()->get('role');
        if ($role !== 'superadmin') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Admin tidak ditemukan.');
        }

        if ((int)$user['id'] === (int)session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus diri sendiri.');
        }

        $userModel->delete($id);

        return redirect()->back()->with('success', 'Akun admin berhasil dihapus.');
    }

    /**
     * GET /admin/locations (Super Admin only)
     */
    public function locations()
    {
        $locationModel = new LocationModel();
        $data['locations'] = $locationModel->orderBy('id', 'DESC')->findAll();
        $data['active_menu'] = 'locations';
        return view('admin/locations', $data);
    }

    /**
     * POST /admin/locations/create
     */
    public function createLocation()
    {
        $name = $this->request->getPost('name');
        if (empty(trim($name))) {
            return redirect()->back()->with('error', 'Nama lokasi wajib diisi.');
        }

        $locationModel = new LocationModel();
        $locationModel->insert(['name' => $name]);

        return redirect()->back()->with('success', 'Lokasi berhasil ditambahkan.');
    }

    /**
     * POST /admin/locations/delete/{id}
     */
    public function deleteLocation($id)
    {
        $locationModel = new LocationModel();
        try {
            $locationModel->delete($id);
            return redirect()->back()->with('success', 'Lokasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus. Lokasi ini masih memiliki relasi data.');
        }
    }

    /**
     * GET /admin/service-units (Super Admin only)
     */
    public function serviceUnits()
    {
        $serviceUnitModel = new ServiceUnitModel();
        $locationModel = new LocationModel();
        
        $data['service_units'] = $serviceUnitModel
            ->select('service_units.*, locations.name as location_name')
            ->join('locations', 'locations.id = service_units.location_id')
            ->orderBy('service_units.id', 'DESC')
            ->findAll();
        $data['locations'] = $locationModel->findAll();
        $data['active_menu'] = 'service_units';
        
        return view('admin/service_units', $data);
    }

    /**
     * POST /admin/service-units/create
     */
    public function createServiceUnit()
    {
        $locationId = $this->request->getPost('location_id');
        $name = $this->request->getPost('name');
        if (empty(trim($name)) || empty($locationId)) {
            return redirect()->back()->with('error', 'Nama unit dan lokasi wajib diisi.');
        }

        $serviceUnitModel = new ServiceUnitModel();
        $serviceUnitModel->insert([
            'location_id' => $locationId,
            'name'        => $name
        ]);

        return redirect()->back()->with('success', 'Unit layanan berhasil ditambahkan.');
    }

    /**
     * POST /admin/service-units/delete/{id}
     */
    public function deleteServiceUnit($id)
    {
        $serviceUnitModel = new ServiceUnitModel();
        try {
            $serviceUnitModel->delete($id);
            return redirect()->back()->with('success', 'Unit layanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus. Unit layanan masih memiliki relasi data.');
        }
    }

    /**
     * GET /admin/categories (Super Admin only)
     */
    public function categories()
    {
        $categoryModel = new ComplaintCategoryModel();
        $locationModel = new LocationModel();
        
        $data['categories'] = $categoryModel
            ->select('complaint_categories.*, locations.name as location_name')
            ->join('locations', 'locations.id = complaint_categories.location_id')
            ->orderBy('complaint_categories.id', 'DESC')
            ->findAll();
        $data['locations'] = $locationModel->findAll();
        $data['active_menu'] = 'categories';
        
        return view('admin/categories', $data);
    }

    /**
     * POST /admin/categories/create
     */
    public function createCategory()
    {
        $locationId = $this->request->getPost('location_id');
        $name = $this->request->getPost('name');
        if (empty(trim($name)) || empty($locationId)) {
            return redirect()->back()->with('error', 'Nama kategori dan lokasi wajib diisi.');
        }

        $categoryModel = new ComplaintCategoryModel();
        $categoryModel->insert([
            'location_id' => $locationId,
            'name'        => $name
        ]);

        return redirect()->back()->with('success', 'Kategori aduan berhasil ditambahkan.');
    }

    /**
     * POST /admin/categories/delete/{id}
     */
    public function deleteCategory($id)
    {
        $categoryModel = new ComplaintCategoryModel();
        try {
            $categoryModel->delete($id);
            return redirect()->back()->with('success', 'Kategori aduan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus. Kategori masih memiliki relasi data.');
        }
    }

    /**
     * GET /admin/analytics (Super Admin only)
     */
    public function analytics()
    {
        $data['monthly_chart'] = $this->repository->getMonthlyChartData();
        $data['category_chart'] = $this->repository->getCategoryChartData();
        $data['location_chart'] = $this->repository->getLocationChartData();
        $data['unit_chart'] = $this->repository->getUnitChartData();
        
        $data['summary_global'] = $this->repository->getAnalyticsSummary();
        $data['summary_dpmptsp'] = $this->repository->getAnalyticsSummary(1);
        $data['summary_mpp'] = $this->repository->getAnalyticsSummary(2);
        
        $data['active_menu'] = 'analytics';
        return view('admin/analytics', $data);
    }

    /**
     * GET /admin/audit-logs (Super Admin only)
     */
    public function auditLogs()
    {
        $currentPage = $this->request->getGet('page') ?: 1;
        $limit = 20;
        $offset = ($currentPage - 1) * $limit;

        $data['logs'] = $this->repository->getAuditLogs($limit, $offset);
        $total = $this->repository->getAuditLogsCount();

        $data['total_pages'] = ceil($total / $limit);
        $data['current_page'] = $currentPage;
        $data['active_menu'] = 'audit_logs';

        return view('admin/audit_logs', $data);
    }

    /**
     * GET /admin/database (Super Admin only)
     */
    public function database()
    {
        $data['active_menu'] = 'database';
        return view('admin/database', $data);
    }

    /**
     * POST /admin/database/backup
     */
    public function backupDatabase()
    {
        $db = \Config\Database::connect();
        $dbName = $db->database;

        $backupDir = WRITEPATH . 'backups/';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'backup_' . $dbName . '_' . date('Ymd_His') . '.sql';
        $filepath = $backupDir . $filename;

        $username = $db->username;
        $password = $db->password;
        $host = $db->hostname;

        $command = "mysqldump -h " . escapeshellarg($host) . " -u " . escapeshellarg($username) . " -p" . escapeshellarg($password) . " " . escapeshellarg($dbName) . " > " . escapeshellarg($filepath);

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return $this->response->download($filepath, null)->setFileName($filename);
        } else {
            return redirect()->back()->with('error', 'Gagal melakukan backup database. Pastikan mysqldump terinstall.');
        }
    }

    /**
     * POST /admin/database/restore
     */
    public function restoreDatabase()
    {
        $file = $this->request->getFile('backup_file');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File backup tidak valid.');
        }

        $db = \Config\Database::connect();
        $dbName = $db->database;
        $username = $db->username;
        $password = $db->password;
        $host = $db->hostname;

        $filepath = $file->getTempName();

        $command = "mysql -h " . escapeshellarg($host) . " -u " . escapeshellarg($username) . " -p" . escapeshellarg($password) . " " . escapeshellarg($dbName) . " < " . escapeshellarg($filepath);

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return redirect()->back()->with('success', 'Database berhasil di-restore.');
        } else {
            return redirect()->back()->with('error', 'Gagal me-restore database.');
        }
    }

    /**
     * POST /admin/complaints/{id}/assign
     */
    public function assignComplaint($id)
    {
        $role = session()->get('role');
        $locationId = $this->getLocationIdFromRole($role);

        $complaint = $this->repository->findById((int)$id);
        if (!$complaint) {
            return redirect()->back()->with('error', 'Pengaduan tidak ditemukan.');
        }

        // Superadmin cannot assign complaints
        if ($role === 'superadmin') {
            return redirect()->back()->with('error', 'Akses ditolak. Super Admin tidak berwenang menugaskan pengaduan.');
        }

        // Check location
        if ($locationId && (int)$complaint['location_id'] !== $locationId) {
            return redirect()->back()->with('error', 'Akses ditolak. Pengaduan bukan milik unit Anda.');
        }

        // Only admin_dpmptsp and admin_mpp can assign
        if (!in_array($role, ['admin_dpmptsp', 'admin_mpp'])) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak memiliki wewenang untuk menugaskan.');
        }

        $adminId = $this->request->getPost('assigned_to') ?: null;
        $adminName = session()->get('name');

        $success = $this->repository->assignComplaint((int)$id, $adminId ? (int)$adminId : null, $adminName);

        if ($success) {
            return redirect()->back()->with('success', 'Penugasan aduan berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui penugasan.');
        }
    }

    /**
     * GET /admin/settings
     */
    public function settings()
    {
        $settingsPath = WRITEPATH . 'settings.json';
        $settings = [
            'maintenance_mode' => false,
            'rate_limit_per_day' => 5
        ];

        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true) ?: $settings;
        }

        $data['settings'] = $settings;
        $data['active_menu'] = 'settings';
        return view('admin/settings', $data);
    }

    /**
     * POST /admin/settings/update
     */
    public function updateSettings()
    {
        $maintenanceMode = $this->request->getPost('maintenance_mode') === '1' ? true : false;
        $rateLimit = (int)$this->request->getPost('rate_limit_per_day') ?: 5;

        $settings = [
            'maintenance_mode' => $maintenanceMode,
            'rate_limit_per_day' => $rateLimit
        ];

        file_put_contents(WRITEPATH . 'settings.json', json_encode($settings, JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil disimpan.');
    }

    /**
     * Helper to map admin role to location ID
     */
    protected function getLocationIdFromRole(string $role)
    {
        if ($role === 'admin_dpmptsp') {
            return 1;
        }
        if ($role === 'admin_mpp') {
            return 2;
        }
        return null;
    }

    /**
     * GET /admin/notifications/unread
     */
    public function getUnreadNotifications()
    {
        $userId = session()->get('user_id');
        $role = session()->get('role');
        $locationId = $this->getLocationIdFromRole($role);
        $serviceUnitId = session()->get('service_unit_id');

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }

        $unreadCount = $this->repository->getUnreadComplaintsCount((int)$userId, $role, $locationId, $serviceUnitId);
        $unreadComplaints = $this->repository->getUnreadComplaints((int)$userId, $role, $locationId, $serviceUnitId, 5);
        $notifHistory = $this->repository->getNotificationHistory((int)$userId, $role, $locationId, $serviceUnitId, 20);

        // Format dates for display
        foreach ($unreadComplaints as &$uc) {
            $uc['formatted_date'] = date('d M Y, H:i', strtotime($uc['created_at']));
        }
        foreach ($notifHistory as &$nh) {
            $nh['formatted_date'] = date('d M Y, H:i', strtotime($nh['created_at']));
            $nh['formatted_read_at'] = $nh['read_at'] ? date('d M, H:i', strtotime($nh['read_at'])) : null;
        }

        return $this->response->setJSON([
            'status' => 'success',
            'unread_count' => $unreadCount,
            'unread_complaints' => $unreadComplaints,
            'notification_history' => $notifHistory
        ]);
    }
}
