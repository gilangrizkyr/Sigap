<?php

namespace App\Repositories;

use App\Models\ComplaintModel;
use App\Models\ComplaintAttachmentModel;
use App\Models\ComplaintReplyModel;
use App\Models\ComplaintStatusLogModel;

class ComplaintRepository
{
    protected $complaintModel;
    protected $attachmentModel;
    protected $replyModel;
    protected $statusLogModel;

    public function __construct()
    {
        $this->complaintModel = new ComplaintModel();
        $this->attachmentModel = new ComplaintAttachmentModel();
        $this->replyModel = new ComplaintReplyModel();
        $this->statusLogModel = new ComplaintStatusLogModel();
    }

    public function createComplaint(array $complaintData, array $attachments = [])
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Save complaint — insert() returns true/false in CI4, check if it failed due to validation or query error
        if ($this->complaintModel->insert($complaintData) === false) {
            $errors = $this->complaintModel->errors();
            $dbError = $db->error();
            $db->transRollback();
            if (!empty($errors)) {
                throw new \Exception("Validasi gagal: " . implode(" | ", $errors));
            }
            if (!empty($dbError['message'])) {
                throw new \Exception("Database error: " . $dbError['message']);
            }
            throw new \Exception("Gagal menyimpan ke database (Alasan tidak diketahui).");
        }
        $complaintId = $this->complaintModel->getInsertID();

        if (!$complaintId) {
            $dbError = $db->error();
            $db->transRollback();
            throw new \Exception("Gagal mendapatkan ID pengaduan setelah insert. " . ($dbError['message'] ?? ''));
        }

        // Save attachments if any
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                $attachment['complaint_id'] = $complaintId;
                $this->attachmentModel->insert($attachment);
            }
        }

        // Write first status log
        $this->statusLogModel->insert([
            'complaint_id' => $complaintId,
            'old_status'   => null,
            'new_status'   => $complaintData['status'],
            'changed_by'   => 'Pelapor (System)',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            $error = $db->error();
            $db->transRollback();
            throw new \Exception("Database error: " . ($error['message'] ?? 'Unknown database error'));
        }

        return $complaintId;
    }

    public function findByTicketAndPin(string $ticketNumber, string $secretPin)
    {
        return $this->complaintModel->where([
            'ticket_number' => $ticketNumber,
            'secret_pin'    => $secretPin
        ])->first();
    }

    public function findById(int $id)
    {
        return $this->complaintModel->find($id);
    }

    public function getDetails(int $id)
    {
        $complaint = $this->complaintModel
            ->select('complaints.*, locations.name as location_name, service_units.name as service_unit_name, complaint_categories.name as category_name, users.name as assigned_admin_name')
            ->join('locations', 'locations.id = complaints.location_id')
            ->join('service_units', 'service_units.id = complaints.service_unit_id', 'left')
            ->join('complaint_categories', 'complaint_categories.id = complaints.category_id')
            ->join('users', 'users.id = complaints.assigned_to', 'left')
            ->where('complaints.id', $id)
            ->first();

        if (!$complaint) {
            return null;
        }

        // Attachments
        $complaint['attachments'] = $this->attachmentModel->where('complaint_id', $id)->findAll();

        // Replies (include admin name)
        $complaint['replies'] = $this->replyModel
            ->select('complaint_replies.*, users.name as admin_name')
            ->join('users', 'users.id = complaint_replies.admin_id', 'left')
            ->where('complaint_id', $id)
            ->orderBy('complaint_replies.created_at', 'ASC')
            ->findAll();

        // Status Logs
        $complaint['status_logs'] = $this->statusLogModel
            ->where('complaint_id', $id)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        return $complaint;
    }

    public function getComplaints(array $filters = [], int $limit = 0, int $offset = 0)
    {
        $selectCols = 'complaints.*, locations.name as location_name, service_units.name as service_unit_name, complaint_categories.name as category_name, users.name as assigned_admin_name';
        if (!empty($filters['current_user_id'])) {
            $selectCols .= ', user_complaint_reads.read_at';
        }

        $builder = $this->complaintModel
            ->select($selectCols)
            ->join('locations', 'locations.id = complaints.location_id')
            ->join('service_units', 'service_units.id = complaints.service_unit_id', 'left')
            ->join('complaint_categories', 'complaint_categories.id = complaints.category_id')
            ->join('users', 'users.id = complaints.assigned_to', 'left');

        if (!empty($filters['current_user_id'])) {
            $builder->join('user_complaint_reads', 'user_complaint_reads.complaint_id = complaints.id AND user_complaint_reads.user_id = ' . (int)$filters['current_user_id'], 'left');
        }

        if (!empty($filters['location_id'])) {
            $builder->where('complaints.location_id', $filters['location_id']);
        }

        if (!empty($filters['service_unit_id'])) {
            $builder->where('complaints.service_unit_id', $filters['service_unit_id']);
        }

        if (!empty($filters['status'])) {
            $builder->where('complaints.status', $filters['status']);
        }

        if (!empty($filters['complaint_type'])) {
            $builder->where('complaints.complaint_type', $filters['complaint_type']);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('complaints.ticket_number', $filters['search'])
                ->orLike('complaints.title', $filters['search'])
                ->orLike('complaints.description', $filters['search'])
                ->orLike('complaints.complainant_name', $filters['search'])
                ->groupEnd();
        }

        $builder->orderBy('complaints.created_at', 'DESC');

        if ($limit > 0) {
            return $builder->findAll($limit, $offset);
        }

        return $builder->findAll();
    }

    public function getComplaintsCount(array $filters = [])
    {
        $builder = $this->complaintModel;

        if (!empty($filters['location_id'])) {
            $builder->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['service_unit_id'])) {
            $builder->where('service_unit_id', $filters['service_unit_id']);
        }

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (!empty($filters['complaint_type'])) {
            $builder->where('complaint_type', $filters['complaint_type']);
        }

        return $builder->countAllResults();
    }

    public function countByIpToday(string $ipAddress)
    {
        return $this->complaintModel
            ->where('ip_address', $ipAddress)
            ->where('created_at >=', date('Y-m-d 00:00:00'))
            ->where('created_at <=', date('Y-m-d 23:59:59'))
            ->countAllResults();
    }

    public function updateStatus(int $id, string $newStatus, string $changedBy)
    {
        $complaint = $this->complaintModel->find($id);
        if (!$complaint) {
            return false;
        }

        $oldStatus = $complaint['status'];
        if ($oldStatus === $newStatus) {
            return true;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Update status
        $this->complaintModel->update($id, ['status' => $newStatus]);

        // Log status change
        $this->statusLogModel->insert([
            'complaint_id' => $id,
            'old_status'   => $oldStatus,
            'new_status'   => $newStatus,
            'changed_by'   => $changedBy,
        ]);

        $db->transComplete();

        return $db->transStatus() !== false;
    }

    public function addReply(int $complaintId, ?int $adminId, string $message, string $changedBy)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Insert reply
        $this->replyModel->insert([
            'complaint_id' => $complaintId,
            'admin_id'      => $adminId,
            'message'       => $message,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        // Auto update complaint status to waiting_response or resolving if admin replies
        // Wait, the prompt says Admin can update status and reply. Let's make sure it updates status or logs.
        // Let's set it to waiting_response if the current status is processing, or keep as is.
        // Let's also log this reply inside status logs or just do standard update.
        // Let's update the updated_at timestamp of the complaint.
        $this->complaintModel->update($complaintId, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        return $db->transStatus() !== false;
    }

    // Dashboard & Analytics
    public function getAnalyticsSummary(?int $locationId = null, ?int $serviceUnitId = null)
    {
        $stats = [];

        // Base query
        $q = $this->complaintModel;
        if ($locationId) {
            $q = $q->where('location_id', $locationId);
        }
        if ($serviceUnitId) {
            $q = $q->where('service_unit_id', $serviceUnitId);
        }

        // Total complaints
        $stats['total'] = (clone $q)->countAllResults();

        // Today's complaints
        $stats['today'] = (clone $q)
            ->where('created_at >=', date('Y-m-d 00:00:00'))
            ->where('created_at <=', date('Y-m-d 23:59:59'))
            ->countAllResults();

        // Pending (submitted + verified + processing + waiting_response)
        $stats['pending'] = (clone $q)
            ->whereIn('status', ['submitted', 'verified', 'processing', 'waiting_response'])
            ->countAllResults();

        // Completed (resolved)
        $stats['resolved'] = (clone $q)
            ->where('status', 'resolved')
            ->countAllResults();

        // Rejected
        $stats['rejected'] = (clone $q)
            ->where('status', 'rejected')
            ->countAllResults();

        // Overdue complaints (e.g., status is submitted or verified and created more than 3 days ago, or not resolved for 7 days)
        // Let's say overdue is: not resolved/rejected and created_at < 3 days ago.
        $stats['overdue'] = (clone $q)
            ->whereNotIn('status', ['resolved', 'rejected'])
            ->where('created_at <', date('Y-m-d H:i:s', strtotime('-3 days')))
            ->countAllResults();

        return $stats;
    }

    public function getMonthlyChartData(?int $locationId = null, ?int $serviceUnitId = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('complaints')
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(id) as count")
            ->where('created_at >=', date('Y-m-d', strtotime('-6 months')));

        if ($locationId) {
            $builder->where('location_id', $locationId);
        }
        if ($serviceUnitId) {
            $builder->where('service_unit_id', $serviceUnitId);
        }

        return $builder->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getCategoryChartData(?int $locationId = null, ?int $serviceUnitId = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('complaints')
            ->select('complaint_categories.name as category, COUNT(complaints.id) as count')
            ->join('complaint_categories', 'complaint_categories.id = complaints.category_id');

        if ($locationId) {
            $builder->where('complaints.location_id', $locationId);
        }
        if ($serviceUnitId) {
            $builder->where('complaints.service_unit_id', $serviceUnitId);
        }

        return $builder->groupBy('complaint_categories.name')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getUnitChartData()
    {
        $db = \Config\Database::connect();
        return $db->table('complaints')
            ->select('service_units.name as unit, COUNT(complaints.id) as count')
            ->join('service_units', 'service_units.id = complaints.service_unit_id')
            ->groupBy('service_units.name')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getLocationChartData()
    {
        $db = \Config\Database::connect();
        return $db->table('complaints')
            ->select('locations.name as location, COUNT(complaints.id) as count')
            ->join('locations', 'locations.id = complaints.location_id')
            ->groupBy('locations.name')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getActiveAdminsCount()
    {
        $db = \Config\Database::connect();
        return $db->table('users')
            ->where('is_active', 1)
            ->countAllResults();
    }

    public function getAuditLogs(int $limit = 50, int $offset = 0)
    {
        $db = \Config\Database::connect();
        return $db->table('complaint_status_logs')
            ->select('complaint_status_logs.*, complaints.ticket_number as ticket_number, complaints.title as complaint_title')
            ->join('complaints', 'complaints.id = complaint_status_logs.complaint_id')
            ->orderBy('complaint_status_logs.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    public function getAuditLogsCount()
    {
        $db = \Config\Database::connect();
        return $db->table('complaint_status_logs')->countAllResults();
    }

    public function assignComplaint(int $complaintId, ?int $adminId, string $changedBy)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Update assigned admin
        $this->complaintModel->update($complaintId, ['assigned_to' => $adminId]);

        // Get admin name
        $adminName = 'Tidak Ditugaskan';
        if ($adminId) {
            $userModel = new \App\Models\UserModel();
            $admin = $userModel->find($adminId);
            if ($admin) {
                $adminName = $admin['name'];
            }
        }

        // Log status change or assignment log inside status logs
        $this->statusLogModel->insert([
            'complaint_id' => $complaintId,
            'old_status'   => null,
            'new_status'   => 'Assigned to: ' . $adminName,
            'changed_by'   => $changedBy,
        ]);

        $db->transComplete();

        return $db->transStatus() !== false;
    }

    public function getUnreadComplaints(int $userId, string $role, ?int $locationId = null, ?int $serviceUnitId = null, int $limit = 5)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('complaints')
            ->select('complaints.id, complaints.ticket_number, complaints.title, complaints.created_at');

        if ($role === 'admin_dpmptsp') {
            $builder->where('complaints.location_id', 1);
        } elseif ($role === 'admin_mpp') {
            $builder->where('complaints.location_id', 2);
        } elseif ($role === 'pic_unit' && $serviceUnitId !== null) {
            $builder->where('complaints.service_unit_id', $serviceUnitId);
        }

        // Exclude read complaints
        $subQuery = $db->table('user_complaint_reads')
            ->select('complaint_id')
            ->where('user_id', $userId);

        $builder->whereNotIn('complaints.id', $subQuery);
        $builder->orderBy('complaints.created_at', 'DESC');
        
        if ($limit > 0) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    public function getUnreadComplaintsCount(int $userId, string $role, ?int $locationId = null, ?int $serviceUnitId = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('complaints');

        if ($role === 'admin_dpmptsp') {
            $builder->where('complaints.location_id', 1);
        } elseif ($role === 'admin_mpp') {
            $builder->where('complaints.location_id', 2);
        } elseif ($role === 'pic_unit' && $serviceUnitId !== null) {
            $builder->where('complaints.service_unit_id', $serviceUnitId);
        }

        $subQuery = $db->table('user_complaint_reads')
            ->select('complaint_id')
            ->where('user_id', $userId);

        $builder->whereNotIn('complaints.id', $subQuery);

        return $builder->countAllResults();
    }

    public function markAsRead(int $complaintId, int $userId)
    {
        $db = \Config\Database::connect();
        $exists = $db->table('user_complaint_reads')
            ->where('user_id', $userId)
            ->where('complaint_id', $complaintId)
            ->countAllResults();

        if ($exists === 0) {
            $db->table('user_complaint_reads')->insert([
                'user_id'      => $userId,
                'complaint_id' => $complaintId,
                'read_at'      => date('Y-m-d H:i:s')
            ]);
            return true;
        }
        return false;
    }

    public function getNotificationHistory(int $userId, string $role, ?int $locationId = null, ?int $serviceUnitId = null, int $limit = 20)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('complaints')
            ->select('complaints.id, complaints.ticket_number, complaints.title, complaints.created_at, user_complaint_reads.read_at')
            ->join('user_complaint_reads', 'user_complaint_reads.complaint_id = complaints.id AND user_complaint_reads.user_id = ' . $db->escape($userId), 'left');

        if ($role === 'admin_dpmptsp') {
            $builder->where('complaints.location_id', 1);
        } elseif ($role === 'admin_mpp') {
            $builder->where('complaints.location_id', 2);
        } elseif ($role === 'pic_unit' && $serviceUnitId !== null) {
            $builder->where('complaints.service_unit_id', $serviceUnitId);
        }

        $builder->orderBy('complaints.created_at', 'DESC');
        
        if ($limit > 0) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }
}
