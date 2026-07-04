<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserComplaintReadsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'complaint_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'read_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'complaint_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('complaint_id', 'complaints', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_complaint_reads');
    }

    public function down()
    {
        $this->forge->dropTable('user_complaint_reads', true);
    }
}
