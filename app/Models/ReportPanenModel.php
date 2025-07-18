<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportPanenModel extends Model
{
    protected $table      = 'report_panen';
    protected $primaryKey = 'report_panen_id';
    protected $allowedFields = [
        'transaksi_id',
        'tanaman_id',
        'tgl_transaksi',
        'hs_id',
        'status_id',
        'berat_timbangan',
        'rfid_tanaman',
        'group_id',
    ];

    /**
     * Aggregated Pokok: jumlah tanaman per bulan
     */
    public function getAggregatedDataPokok(?string $startDate = null, ?string $endDate = null)
    {
        $builder = $this->db->table('report_panen rp')
            ->select("
                DATE_FORMAT(rp.tgl_transaksi, '%Y-%m') AS tanggal,
                pt.pt AS PT,
                pt.estate AS Estate,
                COUNT(*) AS jumlah_pohon
            ")
            ->join('hectare_statement hs', 'rp.hs_id = hs.hs_id')
            ->join('pt_estate pt',          'hs.pt_estate_id = pt.pt_estate_id');

        if ($startDate && $endDate) {
            $builder->where('DATE(rp.tgl_transaksi) >=', $startDate)
                ->where('DATE(rp.tgl_transaksi) <=', $endDate);
        }

        $builder->groupBy(['tanggal', 'pt.pt', 'pt.estate']);

        return $builder->get()->getResultArray();
    }

    /**
     * Aggregated Berat: jumlah tanaman + total berat per bulan
     */
    public function getAggregatedDataWeight(?string $startDate = null, ?string $endDate = null)
    {
        $builder = $this->db->table('report_panen rp')
            ->select(" 
                DATE_FORMAT(rp.tgl_transaksi, '%Y-%m') AS tanggal,
                pt.pt AS PT,
                pt.estate AS Estate,
                COUNT(*) AS jumlah_pohon,
                SUM(rp.berat_timbangan) AS Berat
            ")
            ->join('hectare_statement hs', 'rp.hs_id = hs.hs_id')
            ->join('pt_estate pt',          'hs.pt_estate_id = pt.pt_estate_id');

        if ($startDate && $endDate) {
            $builder->where('DATE(rp.tgl_transaksi) >=', $startDate)
                ->where('DATE(rp.tgl_transaksi) <=', $endDate);
        }

        $builder->groupBy(['tanggal', 'pt.pt', 'pt.estate']);

        return $builder->get()->getResultArray();
    }

    /**
     * Aggregated by Status
     */
    public function getAggregatedDataByStatus(?string $startDate = null, ?string $endDate = null)
    {
        $builder = $this->db->table('report_panen rp')
            ->select(" 
                DATE_FORMAT(rp.tgl_transaksi, '%Y-%m') AS tanggal,
                pt.pt AS PT,
                pt.estate AS Estate,
                s.nama_status AS Status,
                COUNT(*) AS jumlah_pohon
            ")
            ->join('hectare_statement hs', 'rp.hs_id = hs.hs_id')
            ->join('status s',            'rp.status_id = s.status_id')
            ->join('pt_estate pt',        'hs.pt_estate_id = pt.pt_estate_id');

        if ($startDate && $endDate) {
            $builder->where('DATE(rp.tgl_transaksi) >=', $startDate)
                ->where('DATE(rp.tgl_transaksi) <=', $endDate);
        }

        $builder->groupBy(['tanggal', 'pt.pt', 'pt.estate', 's.nama_status']);

        return $builder->get()->getResultArray();
    }

    /**
     * Aggregated by Blok + Status
     */
    public function getAggregatedDataByBlockStatus(?string $startDate = null, ?string $endDate = null)
    {
        $builder = $this->db->table('report_panen rp')
            ->select(" 
                DATE_FORMAT(rp.tgl_transaksi, '%Y-%m') AS tanggal,
                pt.pt AS PT,
                pt.estate AS Estate,
                mb.nama_blok AS Block,
                s.nama_status AS Status,
                COUNT(*) AS jumlah_pohon
            ")
            ->join('hectare_statement hs', 'rp.hs_id = hs.hs_id')
            ->join('pt_estate pt',        'hs.pt_estate_id = pt.pt_estate_id')
            ->join('master_blok mb',      'hs.blok_id = mb.blok_id')
            ->join('status s',            'rp.status_id = s.status_id');

        if ($startDate && $endDate) {
            $builder->where('DATE(rp.tgl_transaksi) >=', $startDate)
                ->where('DATE(rp.tgl_transaksi) <=', $endDate);
        }

        $builder->groupBy(['tanggal', 'pt.pt', 'pt.estate', 'mb.nama_blok', 's.nama_status']);

        return $builder->get()->getResultArray();
    }
}
