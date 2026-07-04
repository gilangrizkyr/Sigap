<?php

namespace App\Models\Dpmptsp;

use CodeIgniter\Model;

/**
 * Model kategori aduan khusus DPMPTSP (location_id = 1).
 */
class CategoryModel extends Model
{
    protected $table            = 'complaint_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['location_id', 'name'];

    protected $useTimestamps = false;

    /**
     * Scope default: hanya ambil kategori milik DPMPTSP (location_id = 1).
     */
    public function findAll(?int $limit = null, int $offset = 0)
    {
        $this->where('location_id', 1);
        return parent::findAll($limit, $offset);
    }
}
