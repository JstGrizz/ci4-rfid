<?php

namespace App\Models;

use CodeIgniter\Model;

class TanamanModel extends Model
{
    protected $table         = 'tanaman';
    protected $primaryKey    = 'tanaman_id';
    // removed 'is_loses'; added 'aktivitas_id'
    protected $allowedFields = [
        'tgl_mulai_identifikasi',
        'hs_id',
        'rfid_tanaman',
        'latitude_tanam',
        'longitude_tanam',
        'no_titik_tanam',
        'status_id',
        'sister',
        'losses_id',
        'deskripsi_loses',
        'tgl_akhir_identifikasi',
        'minggu',
        'nama_karyawan',
        'npk',
        'aktivitas_id',
    ];
    protected $returnType    = 'array';

    public function getNoTitikTanamData($noTitikTanam, $hsId)
    {
        return $this->select('latitude_tanam, longitude_tanam')
            ->where('no_titik_tanam', $noTitikTanam)
            ->where('hs_id', $hsId)
            ->first();
    }

    public function getNoActiveTanamDataSeleksi($noTitikTanam, $hsId, $aktivitasId)
    {
        // Build the query
        $query = $this->select('tanaman.tanaman_id, tanaman.rfid_tanaman, tanaman.sister, tanaman.status_id, status.nama_status')
            ->join('status', 'tanaman.status_id = status.status_id')
            ->join('hectare_statement', 'tanaman.hs_id = hectare_statement.hs_id') // Join hectare_statement
            ->join('tipe_aktivitas', 'tanaman.aktivitas_id = tipe_aktivitas.aktivitas_id') // Join tipe_aktivitas
            ->where('tanaman.no_titik_tanam', $noTitikTanam)
            ->where('tanaman.hs_id', $hsId)  // Add hs_id to the condition
            ->where('tanaman.aktivitas_id', $aktivitasId)  // Add aktivitas_id to the condition
            ->where('tanaman.tgl_mulai_identifikasi IS NOT NULL')
            ->where('tanaman.tgl_akhir_identifikasi IS NULL');
        // Execute and return the results
        return $query->findAll();
    }

    public function getNoActiveTanamDataUpdate($noTitikTanam, $hsId)
    {
        // Build the query with columns in an array
        $query = $this->select([
            'tanaman.tanaman_id',
            'tanaman.rfid_tanaman',
            'tanaman.sister',
            'tanaman.status_id',
            'status.nama_status',
            'tipe_aktivitas.nama_aktivitas',
            'tipe_aktivitas.aktivitas_id',
        ])
            ->join('status', 'tanaman.status_id = status.status_id')
            ->join('hectare_statement', 'tanaman.hs_id = hectare_statement.hs_id') // Join hectare_statement
            ->join('tipe_aktivitas', 'tanaman.aktivitas_id = tipe_aktivitas.aktivitas_id') // Join tipe_aktivitas
            ->where('tanaman.no_titik_tanam', $noTitikTanam)
            ->where('tanaman.hs_id', $hsId)  // Add hs_id to the condition
            ->where('tanaman.tgl_mulai_identifikasi IS NOT NULL')
            ->where('tanaman.tgl_akhir_identifikasi IS NULL');

        // Execute and return the results
        return $query->findAll();
    }

    public function fetchLatestStatusForTitikTanam($longitudeTanam, $latitudeTanam, $noTitikTanam, $hsId)
    {
        return $this->select('status_id')
            ->where('longitude_tanam', $longitudeTanam)
            ->where('latitude_tanam', $latitudeTanam)
            ->where('no_titik_tanam', $noTitikTanam)
            ->where('hs_id', $hsId)
            ->orderBy('tanaman_id', 'DESC')
            ->first()['status_id'] ?? null;
    }

    public function checkIfStatusIsActive($longitudeTanam, $latitudeTanam, $noTitikTanam, $hsId, $statusID)
    {
        $activeCount = $this->where('longitude_tanam', $longitudeTanam)
            ->where('latitude_tanam', $latitudeTanam)
            ->where('no_titik_tanam', $noTitikTanam)
            ->where('hs_id', $hsId)
            ->where('status_id', $statusID)
            ->where('tgl_akhir_identifikasi', null)
            ->countAllResults();

        return $activeCount > 0;
    }

    public function fetchLatestSisterForTitikTanamAndStatus($noTitikTanam, $hsId, $statusId)
    {
        // 1) Fetch all records with the same no_titik_tanam and status_id, including inactive
        $query = $this->where('no_titik_tanam', $noTitikTanam)
            ->where('hs_id', $hsId)
            ->where('status_id', $statusId);

        // 2) Get the max sister value from active records only (those with no tgl_akhir_identifikasi)
        $maxSisterRow = $this->selectMax('sister')
            ->where('tgl_akhir_identifikasi', null)  // Only active records
            ->first();

        // If no active records, fallback to checking inactive records
        $maxSister = $maxSisterRow ? $maxSisterRow['sister'] : 0;

        // 3) Count active records for this status
        $activeCount = $this->where('no_titik_tanam', $noTitikTanam)
            ->where('hs_id', $hsId)
            ->where('status_id', $statusId)
            ->where('tgl_akhir_identifikasi', null)
            ->countAllResults();

        // 4) Calculate the next sister number
        $nextSister = $activeCount > 0 ? $maxSister + 1 : $maxSister;

        return [
            'max_sister' => (int)$maxSister,
            'active_count' => (int)$activeCount,
            'next_sister' => (int)$nextSister,
        ];
    }

    public function updateTanamanData($noTitikTanam, $hsId, $index, $updateData)
    {
        return $this->where('no_titik_tanam', $noTitikTanam)
            ->where('hs_id', $hsId)
            ->where('tanaman_id', $index)
            ->set($updateData)
            ->update();
    }

    public function getActivePlantsForAdminView()
    {
        return $this->select('
        tanaman.sister,
        pt_estate.pt,
        pt_estate.estate,
        master_blok.nama_blok AS blok,
        hectare_statement.varian_bibit,
        tanaman.no_titik_tanam,
        tanaman.rfid_tanaman,
        status.nama_status AS status,
        tanaman.tgl_mulai_identifikasi,
        tanaman.minggu,
        tipe_aktivitas.nama_aktivitas AS tipe_aktivitas
    ')
            ->join('hectare_statement', 'hectare_statement.hs_id = tanaman.hs_id')
            ->join('pt_estate', 'pt_estate.pt_estate_id = hectare_statement.pt_estate_id')
            ->join('master_blok', 'master_blok.blok_id = hectare_statement.blok_id')
            ->join('status', 'status.status_id = tanaman.status_id')
            ->join('tipe_aktivitas', 'tipe_aktivitas.aktivitas_id = tanaman.aktivitas_id')
            ->where('tanaman.tgl_akhir_identifikasi IS NULL')
            ->findAll();
    }

    public function countSeleksiForCurrentYear($currentYear)
    {
        return $this->select('COUNT(*) as count')
            ->join('tipe_aktivitas', 'tipe_aktivitas.aktivitas_id = tanaman.aktivitas_id')
            ->where('YEAR(tanaman.tgl_mulai_identifikasi)', $currentYear)
            ->where('tanaman.tgl_akhir_identifikasi IS NOT NULL')
            ->where('tanaman.losses_id IS NULL')
            ->like('LOWER(tipe_aktivitas.nama_aktivitas)', 'seleksi')
            ->countAllResults();
    }

    public function countShootingForCurrentYear($currentYear)
    {
        return $this->select('COUNT(*) as count')
            ->join('tipe_aktivitas', 'tipe_aktivitas.aktivitas_id = tanaman.aktivitas_id')
            ->where('YEAR(tanaman.tgl_mulai_identifikasi)', $currentYear)
            ->where('tanaman.tgl_akhir_identifikasi IS NOT NULL')
            ->where('tanaman.losses_id IS NULL')
            ->like('LOWER(tipe_aktivitas.nama_aktivitas)', 'shooting')
            ->countAllResults();
    }

    public function countLossesForCurrentYear($currentYear)
    {
        return $this->select('COUNT(*) as count')
            ->where('YEAR(tanaman.tgl_mulai_identifikasi)', $currentYear)
            ->where('tanaman.tgl_akhir_identifikasi IS NOT NULL')
            ->where('tanaman.losses_id IS NOT NULL')
            ->countAllResults();
    }

    public function countAllSisters($noTitik, $hsId, $statusId)
    {
        return $this->where([
            'no_titik_tanam' => $noTitik,
            'hs_id'          => $hsId,
            'status_id'      => $statusId
        ])->countAllResults();
    }
}
