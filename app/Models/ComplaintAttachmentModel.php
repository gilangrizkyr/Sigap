<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintAttachmentModel extends Model
{
    protected $table            = 'complaint_attachments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['complaint_id', 'file_path', 'file_type'];

    protected $useTimestamps = false;
}
