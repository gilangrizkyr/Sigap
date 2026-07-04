<?php

namespace App\Models\Mpp;

use CodeIgniter\Model;

/**
 * Model unit layanan khusus MPP (location_id = 2).
 * MPP memiliki banyak loket instansi yang bisa dipilih pelapor.
 */
class ServiceUnitModel extends Model
{
    protected $table            = 'service_units';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['location_id', 'name'];

    protected $useTimestamps = false;

    /**
     * Scope default: hanya ambil service units milik MPP (location_id = 2).
     */
    public function findAll(?int $limit = null, int $offset = 0)
    {
        $this->where('location_id', 2);
        return parent::findAll($limit, $offset);
    }
}
