<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'role', 'location_id', 'service_unit_id', 'is_active', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'name'            => 'required|min_length[3]|max_length[255]',
        'email'           => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password'        => 'required|min_length[6]',
        'role'            => 'required|in_list[superadmin,admin_dpmptsp,admin_mpp,pic_unit]',
        'location_id'     => 'permit_empty|integer',
        'service_unit_id' => 'permit_empty|integer',
        'is_active'       => 'permit_empty|in_list[0,1]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
