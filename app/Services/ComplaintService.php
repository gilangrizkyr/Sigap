<?php

namespace App\Services;

use App\Repositories\ComplaintRepository;
use CodeIgniter\Files\File;
use Exception;

class ComplaintService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new ComplaintRepository();
    }

    /**
     * Create a new complaint from public input
     */
    public function createComplaint(array $data, string $ipAddress, array $uploadedFiles = [])
    {
        // 1. Rate limit check from settings.json
        $settingsPath = WRITEPATH . 'settings.json';
        $limit = 5;
        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true);
            if (isset($settings['rate_limit_per_day'])) {
                $limit = (int)$settings['rate_limit_per_day'];
            }
        }

        if ($ipAddress !== '127.0.0.1' && $ipAddress !== '::1') {
            $todayCount = $this->repository->countByIpToday($ipAddress);
            if ($todayCount >= $limit) {
                throw new Exception("Batas harian tercapai. Maksimal " . $limit . " pengaduan per IP per hari untuk mencegah spam.");
            }
        }

        // 2. Validate unit-specific rules
        $locationId = (int)$data['location_id'];
        
        // Auto-configure variables based on location
        if ($locationId === 1) { // DPMPTSP
            $data['service_unit_id'] = null; // DPMPTSP has no service unit
        } elseif ($locationId === 2) { // MPP
            if (empty($data['service_unit_id'])) {
                throw new Exception("Unit layanan MPP wajib diisi.");
            }
        } else {
            throw new Exception("Unit layanan tidak valid.");
        }

        // 3. Anonymous Logic
        $name = trim($data['complainant_name'] ?? '');
        $phone = trim($data['complainant_phone'] ?? '');
        $email = trim($data['complainant_email'] ?? '');

        if (empty($name) && empty($phone) && empty($email)) {
            $data['is_anonymous'] = 1;
            $data['complainant_name'] = 'Anonymous';
            $data['complainant_phone'] = null;
            $data['complainant_email'] = null;
        } else {
            $data['is_anonymous'] = 0;
            $data['complainant_name'] = !empty($name) ? $name : 'Identified User';
            $data['complainant_phone'] = !empty($phone) ? $phone : null;
            $data['complainant_email'] = !empty($email) ? $email : null;
        }

        // 4. Generate unique Ticket Number & Secret PIN
        $data['ticket_number'] = $this->generateTicketNumber();
        $data['secret_pin'] = $this->generateSecretPin();
        $data['status'] = 'submitted';
        $data['ip_address'] = $ipAddress;

        // 5. Handle file attachments
        $attachments = [];
        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Validate size (5MB max)
                    if ($file->getSizeByUnit('mb') > 5) {
                        throw new Exception("Ukuran file bukti maksimal 5MB.");
                    }
                    
                    // Validate extension
                    $ext = $file->getClientExtension();
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
                        throw new Exception("Format file bukti hanya mendukung JPG, JPEG, PNG, dan PDF.");
                    }

                    // Move to public/uploads/complaints
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/uploads/complaints', $newName);

                    $attachments[] = [
                        'file_path' => 'uploads/complaints/' . $newName,
                        'file_type' => $file->getClientMimeType()
                    ];
                }
            }
        }

        // 6. Save through repository
        $complaintId = $this->repository->createComplaint($data, $attachments);
        if (!$complaintId) {
            throw new Exception("Gagal menyimpan pengaduan. Silakan coba kembali.");
        }

        return [
            'id'            => $complaintId,
            'ticket_number' => $data['ticket_number'],
            'secret_pin'    => $data['secret_pin']
        ];
    }

    /**
     * Track a complaint via ticket number and pin
     */
    public function trackComplaint(string $ticketNumber, string $secretPin)
    {
        $complaint = $this->repository->findByTicketAndPin($ticketNumber, $secretPin);
        if (!$complaint) {
            throw new Exception("Nomor tiket atau PIN rahasia tidak cocok.");
        }

        return $this->repository->getDetails($complaint['id']);
    }

    /**
     * Generate ticket in format: KM-YYYY-000001
     */
    protected function generateTicketNumber()
    {
        $year = date('Y');
        $count = $this->repository->getComplaintsCount([
            'year' => $year
        ]);
        
        // Let's do a loop just in case a ticket already exists
        $index = $count + 1;
        do {
            $ticketNumber = sprintf("KM-%s-%06d", $year, $index);
            $existing = $this->repository->getComplaints(['search' => $ticketNumber], 1);
            $index++;
        } while (!empty($existing));

        return $ticketNumber;
    }

    /**
     * Generate random 6-digit PIN
     */
    protected function generateSecretPin()
    {
        return str_pad((string)mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
}
