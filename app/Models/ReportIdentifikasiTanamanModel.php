<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportIdentifikasiTanamanModel extends Model
{
    protected $table            = 'report_identifikasi_tanaman';
    protected $primaryKey       = 'report_identifikasi_tanaman_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tanaman_id',
        'tgl_mulai_identifikasi',
        'hs_id',
        'rfid_tanaman',
        'latitude_tanam',
        'longitude_tanam',
        'no_titik_tanam',
        'status_id',
        'sister',
        'is_loses',
        'losses_id',
        'deskripsi_loses',
        'tgl_akhir_identifikasi',
        'minggu',
        'nama_karyawan',
        'npk'
    ];

    /**
     * Contoh method untuk mendapatkan semua record beserta relasi tanamannya.
     */
    public function getAllWithTanaman()
    {
        return $this->select('
                report_identifikasi_tanaman.*,
                t.nama_latin AS tanaman_nama,
                hs.blok_id,
                hs.pt_estate_id
            ')
            ->join('tanaman t', 't.tanaman_id = report_identifikasi_tanaman.tanaman_id', 'left')
            ->join('hectare_statement hs', 'hs.hs_id = report_identifikasi_tanaman.hs_id', 'left')
            ->orderBy('report_identifikasi_tanaman.tgl_mulai_identifikasi', 'DESC')
            ->findAll();
    }
}
