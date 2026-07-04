<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintReplyModel extends Model
{
    protected $table            = 'complaint_replies';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['complaint_id', 'admin_id', 'message', 'created_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at field
}
