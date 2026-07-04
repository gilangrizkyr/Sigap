<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\ComplaintService;
use App\Repositories\ComplaintRepository;
use CodeIgniter\API\ResponseTrait;
use Exception;

class ComplaintController extends BaseController
{
    use ResponseTrait;

    protected $complaintService;
    protected $repository;

    public function __construct()
    {
        $this->complaintService = new ComplaintService();
        $this->repository = new ComplaintRepository();
    }

    /**
     * POST /api/complaints
     * Public endpoint to submit a new complaint.
     */
    public function create()
    {
        // 1. Get request parameters (handle both JSON and standard POST bodies)
        $json = [];
        if (str_contains($this->request->getHeaderLine('Content-Type'), 'application/json')) {
            $json = $this->request->getJSON(true) ?? [];
        }

        $data = [
            'location_id'       => $json['location_id'] ?? $this->request->getPost('location_id'),
            'service_unit_id'   => $json['service_unit_id'] ?? $this->request->getPost('service_unit_id'),
            'category_id'       => $json['category_id'] ?? $this->request->getPost('category_id'),
            'complaint_type'    => $json['complaint_type'] ?? $this->request->getPost('complaint_type'),
            'title'             => $json['title'] ?? $this->request->getPost('title'),
            'description'       => $json['description'] ?? $this->request->getPost('description'),
            'complainant_name'  => $json['complainant_name'] ?? $this->request->getPost('complainant_name'),
            'complainant_phone' => $json['complainant_phone'] ?? $this->request->getPost('complainant_phone'),
            'complainant_email' => $json['complainant_email'] ?? $this->request->getPost('complainant_email'),
        ];

        // 2. Fetch upload files if any
        $files = [];
        if ($this->request->getFiles()) {
            $uploadedFiles = $this->request->getFiles();
            // Handle single or multiple uploads
            if (isset($uploadedFiles['attachments'])) {
                $attachments = $uploadedFiles['attachments'];
                if (is_array($attachments)) {
                    $files = $attachments;
                } else {
                    $files = [$attachments];
                }
            }
        }

        // Get Client IP
        $ip = $this->request->getIPAddress();

        try {
            $result = $this->complaintService->createComplaint($data, $ip, $files);
            return $this->respond([
                'success' => true,
                'message' => 'Pengaduan berhasil dikirim.',
                'data'    => $result
            ], 201);
        } catch (Exception $e) {
            return $this->fail($e->getMessage(), 400);
        }
    }

    /**
     * GET /api/complaints/tracking
     * Public tracking page API.
     */
    public function tracking()
    {
        $ticket = $this->request->getGet('ticket');
        $pin = $this->request->getGet('pin');

        if (empty($ticket) || empty($pin)) {
            return $this->fail('Nomor tiket dan PIN rahasia wajib diisi.', 400);
        }

        try {
            $complaint = $this->complaintService->trackComplaint($ticket, $pin);
            return $this->respond([
                'success' => true,
                'message' => 'Detail pengaduan ditemukan.',
                'data'    => $complaint
            ], 200);
        } catch (Exception $e) {
            return $this->fail($e->getMessage(), 404);
        }
    }

    /**
     * POST /api/uploads
     * Public upload endpoint for async attachments.
     */
    public function upload()
    {
        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            return $this->fail('File tidak valid.', 400);
        }

        if ($file->getSizeByUnit('mb') > 5) {
            return $this->fail('Ukuran file maksimal 5MB.', 400);
        }

        $ext = $file->getClientExtension();
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
            return $this->fail('Format file hanya mendukung JPG, JPEG, PNG, dan PDF.', 400);
        }

        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads/complaints', $newName);

        return $this->respond([
            'success' => true,
            'message' => 'File berhasil diunggah.',
            'data'    => [
                'file_path' => 'uploads/complaints/' . $newName,
                'file_type' => $file->getClientMimeType()
            ]
        ], 200);
    }

    /**
     * GET /api/admin/dashboard
     * Admin dashboard stats.
     */
    public function dashboard()
    {
        $admin = $this->request->adminUser;
        $locationId = $this->getLocationIdFromRole($admin['role']);

        $summary = $this->repository->getAnalyticsSummary($locationId);
        
        return $this->respond([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil.',
            'data'    => [
                'summary' => $summary,
                'charts'  => [
                    'monthly'  => $this->repository->getMonthlyChartData($locationId),
                    'category' => $this->repository->getCategoryChartData($locationId),
                    'unit'     => $locationId === 1 ? [] : $this->repository->getUnitChartData()
                ]
            ]
        ], 200);
    }

    /**
     * GET /api/admin/complaints
     * Admin complaints list.
     */
    public function index()
    {
        $admin = $this->request->adminUser;
        $locationId = $this->getLocationIdFromRole($admin['role']);

        // Merge role restrictions with query filters
        $filters = [
            'location_id'     => $locationId ?: $this->request->getGet('location_id'),
            'service_unit_id' => $this->request->getGet('service_unit_id'),
            'status'          => $this->request->getGet('status'),
            'complaint_type'  => $this->request->getGet('complaint_type'),
            'search'          => $this->request->getGet('search')
        ];

        $limit = (int)($this->request->getGet('limit') ?: 10);
        $offset = (int)($this->request->getGet('offset') ?: 0);

        $complaints = $this->repository->getComplaints($filters, $limit, $offset);
        $total = $this->repository->getComplaintsCount($filters);

        return $this->respond([
            'success' => true,
            'message' => 'Daftar pengaduan berhasil diambil.',
            'data'    => [
                'complaints' => $complaints,
                'pagination' => [
                    'total'  => $total,
                    'limit'  => $limit,
                    'offset' => $offset
                ]
            ]
        ], 200);
    }

    /**
     * GET /api/admin/complaints/{id}
     * Admin complaint details.
     */
    public function show($id)
    {
        $admin = $this->request->adminUser;
        $locationId = $this->getLocationIdFromRole($admin['role']);

        $complaint = $this->repository->getDetails((int)$id);

        if (!$complaint) {
            return $this->fail('Pengaduan tidak ditemukan.', 404);
        }

        // Check if admin is restricted by location
        if ($locationId && (int)$complaint['location_id'] !== $locationId) {
            return $this->failForbidden('Anda tidak memiliki wewenang untuk melihat pengaduan unit ini.');
        }

        return $this->respond([
            'success' => true,
            'message' => 'Detail pengaduan berhasil diambil.',
            'data'    => $complaint
        ], 200);
    }

    /**
     * PUT /api/admin/complaints/{id}/status
     * Admin status update.
     */
    public function updateStatus($id)
    {
        $admin = $this->request->adminUser;
        $locationId = $this->getLocationIdFromRole($admin['role']);

        $complaint = $this->repository->findById((int)$id);
        if (!$complaint) {
            return $this->fail('Pengaduan tidak ditemukan.', 404);
        }

        if ($admin['role'] === 'superadmin') {
            return $this->failForbidden('Super Admin tidak memiliki wewenang untuk memperbarui status pengaduan.');
        }

        if ($locationId && (int)$complaint['location_id'] !== $locationId) {
            return $this->failForbidden('Anda tidak memiliki wewenang untuk memperbarui pengaduan unit ini.');
        }

        $json = $this->request->getJSON(true);
        $newStatus = $json['status'] ?? $this->request->getPost('status');

        $allowedStatus = ['submitted', 'verified', 'processing', 'waiting_response', 'resolved', 'rejected'];
        if (!in_array($newStatus, $allowedStatus)) {
            return $this->fail('Status tidak valid.', 400);
        }

        $success = $this->repository->updateStatus((int)$id, $newStatus, $admin['name']);
        if (!$success) {
            return $this->fail('Gagal memperbarui status.', 500);
        }

        return $this->respond([
            'success' => true,
            'message' => 'Status pengaduan berhasil diperbarui.',
            'data'    => [
                'id'     => $id,
                'status' => $newStatus
            ]
        ], 200);
    }

    /**
     * POST /api/admin/complaints/{id}/reply
     * Admin reply.
     */
    public function reply($id)
    {
        $admin = $this->request->adminUser;
        $locationId = $this->getLocationIdFromRole($admin['role']);

        $complaint = $this->repository->findById((int)$id);
        if (!$complaint) {
            return $this->fail('Pengaduan tidak ditemukan.', 404);
        }

        if ($admin['role'] === 'superadmin') {
            return $this->failForbidden('Super Admin tidak memiliki wewenang untuk membalas pengaduan.');
        }

        if ($locationId && (int)$complaint['location_id'] !== $locationId) {
            return $this->failForbidden('Anda tidak memiliki wewenang untuk membalas pengaduan unit ini.');
        }

        $json = $this->request->getJSON(true);
        $message = $json['message'] ?? $this->request->getPost('message');

        if (empty(trim($message))) {
            return $this->fail('Pesan balasan tidak boleh kosong.', 400);
        }

        // Process reply
        $success = $this->repository->addReply((int)$id, $admin['id'], $message, $admin['name']);
        if (!$success) {
            return $this->fail('Gagal mengirim balasan.', 500);
        }

        // Auto update status to waiting_response or processing if replying
        // Let's say if status is 'submitted' or 'verified', update to 'processing'
        if (in_array($complaint['status'], ['submitted', 'verified'])) {
            $this->repository->updateStatus((int)$id, 'processing', $admin['name']);
        }

        return $this->respond([
            'success' => true,
            'message' => 'Balasan berhasil dikirim.',
            'data'    => null
        ], 200);
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
        return null; // Super admin has no location restriction
    }
}
