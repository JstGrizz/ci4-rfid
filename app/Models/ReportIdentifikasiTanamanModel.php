<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportIdentifikasiTanamanModel extends Model
{
    protected $table         = 'report_identifikasi_tanaman';
    protected $primaryKey    = 'report_identifikasi_tanaman_id';
    protected $allowedFields = [
        'tanaman_id',
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

    /**
     * Laporan jumlah losses, grouped by PT, Estate, Blok.
     */

    public function syncFromTanaman()
    {
        $tanamanModel = new \App\Models\TanamanModel();
        // Ambil semua tanaman yang sudah mulai di-identifikasi
        $all = $tanamanModel
            ->where('tgl_mulai_identifikasi IS NOT NULL')
            ->findAll();

        foreach ($all as $row) {
            // Cek apakah sudah ada entry untuk tanaman_id + tgl_mulai_identifikasi ini
            $exists = $this->where('tanaman_id', $row['tanaman_id'])
                ->where('tgl_mulai_identifikasi', $row['tgl_mulai_identifikasi'])
                ->first();

            if ($exists) {
                // Jika sudah ada, update baris yang sama
                $this->update(
                    $exists['report_identifikasi_tanaman_id'],
                    $row
                );
            } else {
                // Jika belum ada, insert baru
                $this->insert($row);
            }
        }
    }

    /**
     * LOSSES
     */

    // 1) Losses grouped per PT+Estate
    public function getLossesByEstate(string $start, string $end)
    {
        return $this->select([
            'p.pt',
            'p.estate',
            'COUNT(*) AS jumlah_losses',
        ])
            ->join('hectare_statement h', 'report_identifikasi_tanaman.hs_id = h.hs_id')
            ->join('pt_estate p',       'h.pt_estate_id              = p.pt_estate_id')
            ->where('report_identifikasi_tanaman.losses_id IS NOT NULL')
            ->where('DATE(report_identifikasi_tanaman.tgl_mulai_identifikasi) >=', $start)
            ->where('DATE(report_identifikasi_tanaman.tgl_mulai_identifikasi) <=', $end)
            ->groupBy('p.pt, p.estate')
            ->findAll();
    }

    // 2) Losses grouped per PT+Estate+Blok
    public function getLossesByBlock(string $start, string $end)
    {
        return $this->select([
            'p.pt',
            'p.estate',
            'b.nama_blok',
            'COUNT(*) AS jumlah_losses',
        ])
            ->join('hectare_statement h', 'report_identifikasi_tanaman.hs_id = h.hs_id')
            ->join('pt_estate p',       'h.pt_estate_id              = p.pt_estate_id')
            ->join('master_blok b',     'h.blok_id                   = b.blok_id')
            ->where('report_identifikasi_tanaman.losses_id IS NOT NULL')
            ->where('DATE(report_identifikasi_tanaman.tgl_mulai_identifikasi) >=', $start)
            ->where('DATE(report_identifikasi_tanaman.tgl_mulai_identifikasi) <=', $end)
            ->groupBy('p.pt, p.estate, b.nama_blok')
            ->findAll();
    }

    // 3) Losses grouped per PT+Estate+Blok+Titik Tanam
    public function getLossesByTitik(string $start, string $end)
    {
        return $this->select([
            'p.pt',
            'p.estate',
            'b.nama_blok',
            'report_identifikasi_tanaman.no_titik_tanam',
            'COUNT(*) AS jumlah_losses',
        ])
            ->join('hectare_statement h', 'report_identifikasi_tanaman.hs_id = h.hs_id')
            ->join('pt_estate p',       'h.pt_estate_id              = p.pt_estate_id')
            ->join('master_blok b',     'h.blok_id                   = b.blok_id')
            ->where('report_identifikasi_tanaman.losses_id IS NOT NULL')
            ->where('DATE(report_identifikasi_tanaman.tgl_mulai_identifikasi) >=', $start)
            ->where('DATE(report_identifikasi_tanaman.tgl_mulai_identifikasi) <=', $end)
            ->groupBy('p.pt, p.estate, b.nama_blok, report_identifikasi_tanaman.no_titik_tanam')
            ->findAll();
    }


    /**
     * RECOVERY
     */

    // 4) Recovery grouped per PT+Estate
    public function getRecoveryByEstate(string $start, string $end)
    {
        $sql = "
            SELECT p.pt,
                   p.estate,
                   COUNT(*) AS jumlah_recovery
            FROM (
              SELECT
                r.no_titik_tanam,
                r.losses_id,
                LAG(r.losses_id) OVER (
                  PARTITION BY r.no_titik_tanam
                  ORDER BY r.tgl_mulai_identifikasi
                ) AS prev_losses,
                h.pt_estate_id
              FROM report_identifikasi_tanaman r
              JOIN hectare_statement h
                ON r.hs_id = h.hs_id
              WHERE DATE(r.tgl_mulai_identifikasi) BETWEEN ? AND ?
            ) t
            JOIN pt_estate p
              ON t.pt_estate_id = p.pt_estate_id
            WHERE t.prev_losses IS NOT NULL
              AND t.losses_id IS NULL
            GROUP BY p.pt, p.estate
        ";

        return $this->db
            ->query($sql, [$start, $end])
            ->getResultArray();
    }

    /**
     * 5) Recovery grouped per PT+Estate+Blok
     */
    public function getRecoveryByBlock(string $start, string $end)
    {
        $sql = "
            SELECT p.pt,
                   p.estate,
                   b.nama_blok,
                   COUNT(*) AS jumlah_recovery
            FROM (
              SELECT
                r.no_titik_tanam,
                r.losses_id,
                LAG(r.losses_id) OVER (
                  PARTITION BY r.no_titik_tanam
                  ORDER BY r.tgl_mulai_identifikasi
                ) AS prev_losses,
                h.pt_estate_id,
                h.blok_id
              FROM report_identifikasi_tanaman r
              JOIN hectare_statement h
                ON r.hs_id = h.hs_id
              WHERE DATE(r.tgl_mulai_identifikasi) BETWEEN ? AND ?
            ) t
            JOIN pt_estate p
              ON t.pt_estate_id = p.pt_estate_id
            JOIN master_blok b
              ON t.blok_id = b.blok_id
            WHERE t.prev_losses IS NOT NULL
              AND t.losses_id IS NULL
            GROUP BY p.pt, p.estate, b.nama_blok
        ";

        return $this->db
            ->query($sql, [$start, $end])
            ->getResultArray();
    }

    /**
     * 6) Recovery grouped per PT+Estate+Blok+Titik Tanam
     */
    public function getRecoveryByTitik(string $start, string $end)
    {
        $sql = "
            SELECT p.pt,
                   p.estate,
                   b.nama_blok,
                   t.no_titik_tanam,
                   COUNT(*) AS jumlah_recovery
            FROM (
              SELECT
                r.no_titik_tanam,
                r.losses_id,
                LAG(r.losses_id) OVER (
                  PARTITION BY r.no_titik_tanam
                  ORDER BY r.tgl_mulai_identifikasi
                ) AS prev_losses,
                h.pt_estate_id,
                h.blok_id
              FROM report_identifikasi_tanaman r
              JOIN hectare_statement h
                ON r.hs_id = h.hs_id
              WHERE DATE(r.tgl_mulai_identifikasi) BETWEEN ? AND ?
            ) t
            JOIN pt_estate p
              ON t.pt_estate_id = p.pt_estate_id
            JOIN master_blok b
              ON t.blok_id = b.blok_id
            WHERE t.prev_losses IS NOT NULL
              AND t.losses_id IS NULL
            GROUP BY p.pt, p.estate, b.nama_blok, t.no_titik_tanam
        ";

        return $this->db
            ->query($sql, [$start, $end])
            ->getResultArray();
    }
}
