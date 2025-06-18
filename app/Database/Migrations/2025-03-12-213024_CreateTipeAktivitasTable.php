<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTipeAktivitasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'aktivitas_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_aktivitas' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
        ]);

        // Primary key
        $this->forge->addKey('aktivitas_id', true);

        // Create the table
        $this->forge->createTable('tipe_aktivitas');
    }

    public function down()
    {
        $this->forge->dropTable('tipe_aktivitas');
    }
}
