<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportIdentifikasiTanaman extends Migration
{
    public function up()
    {
        // definisi kolom
        $this->forge->addField([
            'report_identifikasi_tanaman_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanaman_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => false,
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
            'is_loses' => [
                'type'       => 'CHAR',
                'constraint' => '1',
                'default'    => 'N',
            ],
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
        ]);

        // primary key
        $this->forge->addKey('report_identifikasi_tanaman_id', true);

        // foreign keys
        $this->forge->addForeignKey('tanaman_id', 'tanaman', 'tanaman_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('hs_id',       'hectare_statement', 'hs_id',       'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('status_id',   'status',            'status_id',   'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('losses_id',   'master_losses',     'losses_id',   'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('npk',         'karyawan',          'npk',         'SET NULL', 'CASCADE');

        // buat table
        $this->forge->createTable('report_identifikasi_tanaman');
    }

    public function down()
    {
        $this->forge->dropTable('report_identifikasi_tanaman');
    }
}
