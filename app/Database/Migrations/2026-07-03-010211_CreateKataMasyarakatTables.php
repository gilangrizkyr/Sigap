<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKataMasyarakatTables extends Migration
{
    public function up()
    {
        // 1. Locations Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('locations');

        // 2. Service Units Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'location_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('service_units');

        // 3. Users Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['superadmin', 'admin_dpmptsp', 'admin_mpp', 'pic_unit'],
            ],
            'location_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'service_unit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('service_unit_id', 'service_units', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('users');

        // 4. Complaint Categories Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'location_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('complaint_categories');

        // 5. Complaints Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'secret_pin' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'complaint_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Pengaduan', 'Aspirasi', 'Saran', 'Apresiasi'],
            ],
            'location_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'service_unit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'assigned_to' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'complainant_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'complainant_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'complainant_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'is_anonymous' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['submitted', 'verified', 'processing', 'waiting_response', 'resolved', 'rejected'],
                'default'    => 'submitted',
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('ticket_number');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('service_unit_id', 'service_units', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'complaint_categories', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('assigned_to', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('complaints');

        // 6. Complaint Attachments Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'complaint_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'file_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('complaint_id', 'complaints', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('complaint_attachments');

        // 7. Complaint Replies Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'complaint_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'admin_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('complaint_id', 'complaints', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('admin_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('complaint_replies');

        // 8. Complaint Status Logs Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'complaint_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'old_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'new_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'changed_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('complaint_id', 'complaints', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('complaint_status_logs');
    }

    public function down()
    {
        // Drop in reverse order to respect foreign key constraints
        $this->forge->dropTable('complaint_status_logs', true);
        $this->forge->dropTable('complaint_replies', true);
        $this->forge->dropTable('complaint_attachments', true);
        $this->forge->dropTable('complaints', true);
        $this->forge->dropTable('complaint_categories', true);
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('service_units', true);
        $this->forge->dropTable('locations', true);
    }
}
