<?php

namespace App\Models\Dpmptsp;

use CodeIgniter\Model;

/**
 * Model complaint khusus DPMPTSP (location_id = 1).
 */
class ComplaintModel extends Model
{
    protected $table            = 'complaints';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ticket_number', 'secret_pin', 'complaint_type', 'location_id',
        'service_unit_id', 'category_id', 'title', 'description',
        'complainant_name', 'complainant_phone', 'complainant_email',
        'is_anonymous', 'status', 'ip_address', 'created_at', 'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'ticket_number'     => 'required|is_unique[complaints.ticket_number,id,{id}]',
        'secret_pin'        => 'required|min_length[4]|max_length[10]',
        'complaint_type'    => 'required|in_list[Pengaduan,Aspirasi,Saran,Apresiasi]',
        'location_id'       => 'required|integer',
        'service_unit_id'   => 'permit_empty|integer',
        'category_id'       => 'required|integer',
        'title'             => 'required|min_length[5]|max_length[255]',
        'description'       => 'required|min_length[10]',
        'complainant_name'  => 'permit_empty|max_length[255]',
        'complainant_phone' => 'permit_empty|max_length[50]',
        'complainant_email' => 'permit_empty|valid_email|max_length[255]',
        'is_anonymous'      => 'required|in_list[0,1]',
        'status'            => 'required|in_list[submitted,verified,processing,waiting_response,resolved,rejected]',
        'ip_address'        => 'required|valid_ip',
    ];

    /**
     * Override findAll — selalu filter ke lokasi DPMPTSP (location_id = 1).
     */
    public function findAll(?int $limit = null, int $offset = 0)
    {
        $this->where('location_id', 1);
        return parent::findAll($limit, $offset);
    }
}
