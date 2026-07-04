<?php

namespace App\Models;

use CodeIgniter\Model;

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
        'is_anonymous', 'status', 'ip_address', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'ticket_number'     => 'required|is_unique[complaints.ticket_number,id,{id}]',
        'secret_pin'        => 'required|min_length[4]|max_length[10]',
        'complaint_type'    => 'required|in_list[Pengaduan,Aspirasi,Saran,Apresiasi]',
        'location_id'       => 'required|integer',
        'service_unit_id'   => 'permit_empty|integer',
        'category_id'       => 'required|integer',
        'title'             => 'required|max_length[255]',
        'description'       => 'required',
        'complainant_name'  => 'permit_empty|max_length[255]',
        'complainant_phone' => 'permit_empty|max_length[50]',
        'complainant_email' => 'permit_empty|valid_email|max_length[255]',
        'is_anonymous'      => 'required|in_list[0,1]',
        'status'            => 'required|in_list[submitted,verified,processing,waiting_response,resolved,rejected]',
        'ip_address'        => 'required|valid_ip',
    ];

    protected $validationMessages = [
        'ticket_number' => [
            'required' => 'Nomor tiket wajib diisi.',
            'is_unique' => 'Nomor tiket sudah terdaftar.',
        ],
        'secret_pin' => [
            'required' => 'PIN rahasia wajib diisi.',
            'min_length' => 'PIN rahasia minimal 4 karakter.',
            'max_length' => 'PIN rahasia maksimal 10 karakter.',
        ],
        'complaint_type' => [
            'required' => 'Klasifikasi laporan wajib dipilih.',
            'in_list' => 'Klasifikasi laporan tidak valid.',
        ],
        'location_id' => [
            'required' => 'Lokasi aduan wajib diisi.',
            'integer' => 'Lokasi aduan harus berupa angka.',
        ],
        'service_unit_id' => [
            'integer' => 'Unit layanan harus berupa angka.',
        ],
        'category_id' => [
            'required' => 'Kategori layanan wajib dipilih.',
            'integer' => 'Kategori layanan harus berupa angka.',
        ],
        'title' => [
            'required' => 'Judul laporan wajib diisi.',
            'max_length' => 'Judul laporan maksimal 255 karakter.',
        ],
        'description' => [
            'required' => 'Isi laporan / kronologi wajib diisi.',
        ],
        'complainant_name' => [
            'max_length' => 'Nama pelapor maksimal 255 karakter.',
        ],
        'complainant_phone' => [
            'max_length' => 'Nomor WhatsApp maksimal 50 karakter.',
        ],
        'complainant_email' => [
            'valid_email' => 'Format email tidak valid.',
            'max_length' => 'Email maksimal 255 karakter.',
        ],
        'is_anonymous' => [
            'required' => 'Pilihan anonim wajib diisi.',
            'in_list' => 'Pilihan anonim tidak valid.',
        ],
    ];
}
