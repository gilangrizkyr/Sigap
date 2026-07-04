<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintCategoryModel extends Model
{
    protected $table            = 'complaint_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['location_id', 'name'];

    protected $useTimestamps = false;
}
