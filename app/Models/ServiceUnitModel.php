<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceUnitModel extends Model
{
    protected $table            = 'service_units';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['location_id', 'name'];

    protected $useTimestamps = false;
}
