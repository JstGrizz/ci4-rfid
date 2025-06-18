<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTanamanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tanaman_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tgl_mulai_identifikasi' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'hs_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'rfid_tanaman' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'latitude_tanam' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
            ],
            'longitude_tanam' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
            'no_titik_tanam' => [
                'type' => 'INT',
                'null' => true,
            ],
            'status_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'sister' => [
                'type' => 'INT',
                'null' => true,
            ],
            // -- removed 'is_loses' column --
            'losses_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'deskripsi_loses' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tgl_akhir_identifikasi' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'minggu' => [
                'type' => 'INT',
                'null' => false,
            ],
            'nama_karyawan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'npk' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            // -- new foreign-key column --
            'aktivitas_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
        ]);

        // Primary key
        $this->forge->addKey('tanaman_id', true);

        // Foreign keys
        $this->forge->addForeignKey('hs_id', 'hectare_statement', 'hs_id');
        $this->forge->addForeignKey('status_id', 'status', 'status_id');
        $this->forge->addForeignKey('losses_id', 'master_losses', 'losses_id');
        $this->forge->addForeignKey('npk', 'karyawan', 'npk');
        $this->forge->addForeignKey('aktivitas_id', 'tipe_aktivitas', 'aktivitas_id');

        // Create table
        $this->forge->createTable('tanaman');
    }

    public function down()
    {
        $this->forge->dropTable('tanaman');
    }
}
