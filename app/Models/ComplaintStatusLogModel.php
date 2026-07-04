<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintStatusLogModel extends Model
{
    protected $table            = 'complaint_status_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['complaint_id', 'old_status', 'new_status', 'changed_by', 'created_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at field
}
