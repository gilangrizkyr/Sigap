<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KataMasyarakatSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Locations
        $locations = [
            ['id' => 1, 'name' => 'DPMPTSP'],
            ['id' => 2, 'name' => 'MPP']
        ];
        $this->db->table('locations')->insertBatch($locations);

        // 2. Seed Service Units for MPP (Location ID: 2)
        $serviceUnits = [
            ['location_id' => 2, 'name' => 'Front Office'],
            ['location_id' => 2, 'name' => 'Tenant BPJS'],
            ['location_id' => 2, 'name' => 'Tenant BRI'],
            ['location_id' => 2, 'name' => 'Tenant Dukcapil'],
            ['location_id' => 2, 'name' => 'Lainnya']
        ];
        $this->db->table('service_units')->insertBatch($serviceUnits);

        // 3. Seed Complaint Categories
        // Categories for DPMPTSP (Location ID: 1)
        $dpmptspCategories = [
            ['location_id' => 1, 'name' => 'Perizinan'],
            ['location_id' => 1, 'name' => 'OSS/NIB'],
            ['location_id' => 1, 'name' => 'Investasi'],
            ['location_id' => 1, 'name' => 'Petugas'],
            ['location_id' => 1, 'name' => 'Fasilitas'],
            ['location_id' => 1, 'name' => 'Lainnya']
        ];
        // Categories for MPP (Location ID: 2)
        $mppCategories = [
            ['location_id' => 2, 'name' => 'Perizinan/Layanan'],
            ['location_id' => 2, 'name' => 'Petugas'],
            ['location_id' => 2, 'name' => 'Fasilitas'],
            ['location_id' => 2, 'name' => 'Antrean'],
            ['location_id' => 2, 'name' => 'Lainnya']
        ];
        $this->db->table('complaint_categories')->insertBatch(array_merge($dpmptspCategories, $mppCategories));

        // 4. Seed Administrative Users
        $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $users = [
            [
                'name'            => 'Global Super Admin',
                'email'           => 'superadmin@katamasyarakat.local',
                'password'        => $defaultPassword,
                'role'            => 'superadmin',
                'location_id'     => null,
                'service_unit_id' => null,
                'is_active'       => 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s')
            ],
            [
                'name'            => 'Admin DPMPTSP',
                'email'           => 'admin.dpmptsp@katamasyarakat.go.id',
                'password'        => $defaultPassword,
                'role'            => 'admin_dpmptsp',
                'location_id'     => 1,
                'service_unit_id' => null,
                'is_active'       => 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s')
            ],
            [
                'name'            => 'Admin MPP',
                'email'           => 'admin.mpp@katamasyarakat.go.id',
                'password'        => $defaultPassword,
                'role'            => 'admin_mpp',
                'location_id'     => 2,
                'service_unit_id' => null,
                'is_active'       => 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('users')->insertBatch($users);
    }
}
