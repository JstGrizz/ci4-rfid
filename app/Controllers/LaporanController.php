<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TimbanganModel;
use App\Models\ReportPanenModel;
use App\Models\MasterBlokModel;
use App\Models\HectareStatementModel;
use App\Models\PolicyModel;
use App\Models\TanamanModel;
use App\Models\StatusModel;
use App\Models\MasterLossesModel;
use App\Models\HistoryLossesModel;
use Fpdf\Fpdf;
use App\Models\ReportIdentifikasiTanamanModel;


class LaporanController extends ResourceController
{

    public function laporanPanenBulanan()
    {
        $timbanganModel = new TimbanganModel();
        $reportPanenModel = new ReportPanenModel();

        // Fetch data from 'timbangan' using ORM
        $timbanganData = $timbanganModel->findAll();

        if (!empty($timbanganData)) {
            foreach ($timbanganData as $row) {
                // Check if the data already exists in 'report_panen' table
                $existingData = $reportPanenModel->where('transaksi_id', $row['transaksi_id'])->first();

                if (!$existingData) { // If no duplicate, insert the data
                    $dataToInsert = [
                        'transaksi_id' => $row['transaksi_id'],
                        'tgl_transaksi' => $row['tgl_transaksi'],
                        'hs_id' => $row['hs_id'],
                        'status_id' => $row['status_id'],
                        'berat_timbangan' => $row['berat_timbangan'],
                        'rfid_tanaman' => $row['rfid_tanaman'],
                        'group_id' => $row['group_id'],
                    ];

                    // Insert the data into 'report_panen'
                    if ($reportPanenModel->insert($dataToInsert)) {
                    } else {
                    }
                } else {
                }
            }
        } else {
            // echo "No data found in timbangan table.\n";
        }

        return view('admin/laporan-panen-bulanan');
    }

    // Fetch report data based on the request body (start_date, end_date, report_type)
    public function fetchReportData()
    {
        $start_date  = $this->request->getGet('start_date');
        $end_date    = $this->request->getGet('end_date');
        $type        = $this->request->getGet('report_type');

        $m = new ReportPanenModel();

        switch ($type) {
            case 'pokok':
                $allData = $m->getAggregatedDataPokok($start_date, $end_date);
                break;
            case 'weight':
                $allData = $m->getAggregatedDataWeight($start_date, $end_date);
                break;
            case 'status':
                $allData = $m->getAggregatedDataByStatus($start_date, $end_date);
                break;
            case 'blok_status':
                $allData = $m->getAggregatedDataByBlockStatus($start_date, $end_date);
                break;
            default:
                return $this->response->setJSON([]);
        }

        $final = [];
        foreach ($allData as $d) {
            $MY = $d['tanggal']; // sudah 'YYYY-MM'
            switch ($type) {
                case 'pokok':
                    $final[] = [
                        'MonthYear'      => $MY,
                        'PT'             => $d['PT'],
                        'Estate'         => $d['Estate'],
                        'jumlah_tanaman' => (int)$d['jumlah_pohon'],
                    ];
                    break;
                case 'weight':
                    $avg = $d['jumlah_pohon']
                        ? $d['Berat'] / $d['jumlah_pohon']
                        : 0;
                    $final[] = [
                        'MonthYear'      => $MY,
                        'PT'             => $d['PT'],
                        'Estate'         => $d['Estate'],
                        'jumlah_tanaman' => (int)$d['jumlah_pohon'],
                        'TotalWeight'    => (float)$d['Berat'],
                        'AverageTandan'  => round($avg, 2),
                    ];
                    break;
                case 'status':
                    $final[] = [
                        'MonthYear'      => $MY,
                        'PT'             => $d['PT'],
                        'Estate'         => $d['Estate'],
                        'Status'         => $d['Status'],
                        'jumlah_tanaman' => (int)$d['jumlah_pohon'],
                    ];
                    break;
                case 'blok_status':
                    $final[] = [
                        'MonthYear'      => $MY,
                        'PT'             => $d['PT'],
                        'Estate'         => $d['Estate'],
                        'Block'          => $d['Block'],
                        'Status'         => $d['Status'],
                        'jumlah_tanaman' => (int)$d['jumlah_pohon'],
                    ];
                    break;
            }
        }

        return $this->response->setJSON($final);
    }

    public function laporanPrediksiPanen()
    {

        $hectareStatementModel = new HectareStatementModel();
        $data['ptEstates'] = $hectareStatementModel->getUniquePtEstates();

        // Nilai default untuk fields
        $data['pt'] = '';
        $data['estate'] = '';
        $data['bloks'] = [];
        $data['tahun_tanam'] = '';
        $data['bulan_tanam'] = '';
        $data['luas_tanah'] = '';
        $data['week'] = '';
        $data['varian_bibit'] = '';

        // Jika request adalah POST (saat form disubmit), ini akan dieksekusi.
        // Untuk dropdown policy, kita akan gunakan AJAX terpisah.
        if ($this->request->getMethod() === 'post') {
            // Ambil PT dan Estate yang dipilih
            $ptEstateId = $this->request->getPost('pt_estate');
            $blokId = $this->request->getPost('blok_id');

            // Ambil detail PT dan Estate dari hectare_statement
            $ptEstate = $hectareStatementModel->getPtEstateDetails($ptEstateId);
            if ($ptEstate) {
                $data['pt'] = $ptEstate['pt'];
                $data['estate'] = $ptEstate['estate'];

                // Ambil blok terkait dengan PT Estate yang dipilih dan ada di hectare_statement
                $masterBlokModel = new MasterBlokModel(); // Inisialisasi model di sini
                $data['bloks'] = $masterBlokModel->getBloksInHectareStatement($ptEstateId);

                // Ambil detail blok yang dipilih dan ambil hectare statement
                if ($blokId) {
                    $hectarStatement = $hectareStatementModel->getHectareStatementByPtEstateIdAndBlockId($ptEstateId, $blokId);

                    if ($hectarStatement) {
                        $data['tahun_tanam'] = $hectarStatement['tahun_tanam'];
                        $data['bulan_tanam'] = $hectarStatement['bulan_tanam'];
                        $data['luas_tanah'] = $hectarStatement['luas_tanah'];
                        $data['varian_bibit'] = $hectarStatement['varian_bibit'];

                        // Hitung minggu
                        $data['week'] = $this->calculateWeek($hectarStatement['tanggal_tanam']);
                    }
                }
            }
        }

        return view('admin/laporan-prediksi-panen', $data);
    }

    public function getBloksByPtEstateId($ptEstateId)
    {
        $blokModel = new MasterBlokModel();
        $bloks = $blokModel->getBloksByPtEstateId($ptEstateId);

        return $this->response->setJSON(['bloks' => $bloks]);
    }

    public function getHectareStatementByPtEstateIdAndBlockId($ptEstateId, $blokId)
    {
        $hectareStatementModel = new HectareStatementModel();
        $hectarStatement = $hectareStatementModel->getHectareStatementByPtEstateIdAndBlockId($ptEstateId, $blokId);

        if ($hectarStatement) {
            $hectarStatement['week'] = $this->calculateWeek($hectarStatement['tanggal_tanam']);
            return $this->response->setJSON($hectarStatement);
        }

        return $this->response->setJSON([]);
    }

    // METHOD BARU UNTUK MENGAMBIL DATA POLICY
    public function getPolicies()
    {
        $policyModel = new PolicyModel();
        $policies = $policyModel->findAll();

        return $this->response->setJSON(['policies' => $policies]);
    }

    private function calculateWeek($tanggalTanam)
    {
        $tanggalTanam = new \DateTime($tanggalTanam);
        $today = new \DateTime();
        $interval = $today->diff($tanggalTanam);
        return floor($interval->days / 7);
    }

    public function fetchPrediksiData()
    {
        // 1) Ambil parameter GET
        $ptEstateId = $this->request->getGet('pt_estate');
        $blokId     = $this->request->getGet('blok_id');
        $policyId   = $this->request->getGet('policy_id');

        if (! $ptEstateId || ! $blokId || ! $policyId) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'pt_estate, blok_id, dan policy_id wajib diisi.']);
        }

        // 2) Ambil HS
        $hs = (new HectareStatementModel())
            ->getHectareStatementByPtEstateIdAndBlockId($ptEstateId, $blokId);
        if (! $hs) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Hectare statement tidak ditemukan.']);
        }

        // 3) Ambil policy
        $policy = (new PolicyModel())->find($policyId);
        if (! $policy) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Policy tidak ditemukan.']);
        }

        // 4) Ambil semua tanaman belum panen
        $tanamans = (new TanamanModel())
            ->where('hs_id', $hs['hs_id'])
            ->where('tgl_akhir_identifikasi', null)
            ->findAll();

        // 5) Load semua status, bikin map [status_id => nama_status]
        $statusModel = new StatusModel();
        $allStatuses = $statusModel->findAll();
        $statusMap   = [];
        foreach ($allStatuses as $s) {
            $statusMap[$s['status_id']] = $s['nama_status'];
        }

        $now        = new \DateTime();
        $akhirDate  = $policy['akhir'] !== '0000-00-00 00:00:00'
            ? new \DateTime($policy['akhir'])
            : null;
        $startDate  = $policy['start'] !== '0000-00-00 00:00:00'
            ? new \DateTime($policy['start'])
            : null;

        $report = [];
        foreach ($tanamans as $t) {
            // a) hitung minggu sejak identifikasi
            $tglIdent        = new \DateTime($t['tgl_mulai_identifikasi']);
            $weeksSinceIdent = floor($now->diff($tglIdent)->days / 7);

            // b) umur awal (kolom minggu)
            $initialWeek = (int)$t['minggu'];

            // c) total umur sekarang
            $currentAge = $initialWeek + $weeksSinceIdent;

            // d) pilih threshold policy
            if ($akhirDate && $tglIdent > $akhirDate) {
                $threshold = (int)$policy['lama'];
            } elseif ($startDate && $tglIdent > $startDate) {
                $threshold = (int)$policy['baru'];
            } else {
                $threshold = (int)$policy['lama'];
            }

            if ($threshold <= 0) {
                continue; // abaikan kalau policy invalid
            }

            // e) filter hanya umur ≥ (threshold − 1)
            if ($currentAge < $threshold - 1) {
                continue;
            }

            // f) ambil nama status dari status_id
            $namaStatus = $statusMap[$t['status_id']] ?? '—';

            // g) tambahkan ke hasil
            $report[] = [
                'PT'                  => $hs['pt'],
                'Estate'              => $hs['estate'],
                'Blok'                => $hs['nama_blok'] ?? $hs['blok_id'],
                'TanggalIdentifikasi' => $t['tgl_mulai_identifikasi'],
                'RFIDTanaman'         => $t['rfid_tanaman'],
                'NoTitikTanam'        => $t['no_titik_tanam'],
                'Longitude'           => $t['longitude_tanam'],
                'Latitude'            => $t['latitude_tanam'],
                'Status'              => $namaStatus,
                'Sister'              => $t['sister'],
                'Umur'                => $currentAge,
            ];
        }

        return $this->response->setJSON($report);
    }

    public function fetchAllPrediksiData()
    {
        // 1) Ambil parameter GET: policy_id
        $policyId = $this->request->getGet('policy_id');

        // Validate policy_id is provided
        if (! $policyId) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'policy_id wajib diisi.']);
        }

        // 2) Ambil policy
        $policy = (new PolicyModel())->find($policyId);
        if (! $policy) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Policy tidak ditemukan.']);
        }

        // 3) Ambil semua tanaman belum panen
        $tanamans = (new TanamanModel())
            ->where('tgl_akhir_identifikasi', null)
            ->findAll();

        // If no unharvested plants found, return an empty array
        if (empty($tanamans)) {
            return $this->response->setJSON([]);
        }

        // 4) Load semua status, bikin map [status_id => nama_status]
        $statusModel = new StatusModel();
        $allStatuses = $statusModel->findAll();
        $statusMap = [];
        foreach ($allStatuses as $s) {
            $statusMap[$s['status_id']] = $s['nama_status'];
        }

        // 5) Initialize HectareStatementModel for later use
        $hectareStatementModel = new HectareStatementModel();

        $now = new \DateTime();

        // Prepare policy dates for comparison
        $akhirDate  = $policy['akhir'] !== '0000-00-00 00:00:00'
            ? new \DateTime($policy['akhir'])
            : null;
        $startDate  = $policy['start'] !== '0000-00-00 00:00:00'
            ? new \DateTime($policy['start'])
            : null;

        $report = [];

        foreach ($tanamans as $t) {
            // a) Get Hectare Statement for the current plant
            // Join with master_blok and pt_estate to get full details
            $hs = $hectareStatementModel
                ->select('hectare_statement.*, master_blok.nama_blok, pt_estate.pt, pt_estate.estate')
                ->join('master_blok', 'master_blok.blok_id = hectare_statement.blok_id', 'left')
                ->join('pt_estate', 'pt_estate.pt_estate_id = hectare_statement.pt_estate_id', 'left')
                ->where('hs_id', $t['hs_id'])
                ->first();

            // Skip if no hectare statement found for the plant
            if (!$hs) {
                continue;
            }

            // b) Hitung minggu sejak identifikasi
            $tglIdent        = new \DateTime($t['tgl_mulai_identifikasi']);
            $weeksSinceIdent = floor($now->diff($tglIdent)->days / 7);

            // c) Umur awal (kolom minggu)
            $initialWeek = (int)$t['minggu'];

            // d) Total umur sekarang
            $currentAge = $initialWeek + $weeksSinceIdent;

            // e) Pilih threshold policy based on tgl_mulai_identifikasi
            if ($akhirDate && $tglIdent > $akhirDate) {
                $threshold = (int)$policy['lama'];
            } elseif ($startDate && $tglIdent > $startDate) {
                $threshold = (int)$policy['baru'];
            } else {
                // Default to 'lama' if neither condition matches (e.g., date before start or '0000-00-00')
                $threshold = (int)$policy['lama'];
            }

            // Ensure threshold is valid to prevent division by zero or illogical filters
            if ($threshold <= 0) {
                continue; // Skip if policy threshold is invalid or zero
            }

            // f) Filter only umur ≥ (threshold − 1)
            if ($currentAge < $threshold - 1) {
                continue;
            }

            // g) Ambil nama status dari status_id
            $namaStatus = $statusMap[$t['status_id']] ?? '—';

            // h) Tambahkan ke hasil
            $report[] = [
                'PT'                    => $hs['pt'] ?? '—',
                'Estate'                => $hs['estate'] ?? '—',
                'Blok'                  => $hs['nama_blok'] ?? ($hs['blok_id'] ?? '—'),
                'TanggalIdentifikasi'   => $t['tgl_mulai_identifikasi'],
                'RFIDTanaman'           => $t['rfid_tanaman'],
                'NoTitikTanam'          => $t['no_titik_tanam'],
                'Longitude'             => $t['longitude_tanam'],
                'Latitude'              => $t['latitude_tanam'],
                'Status'                => $namaStatus,
                'Sister'                => $t['sister'],
                'Umur'                  => $currentAge,
            ];
        }

        return $this->response->setJSON($report);
    }

    public function laporanIdentifikasiTanaman()
    {
        // Sinkronisasi seluruh data tanaman ke report_identifikasi_tanaman
        (new ReportIdentifikasiTanamanModel())->syncFromTanaman();

        return view('admin/laporan-identifikasi-tanaman');
    }

    /**
     * Endpoint AJAX untuk fetch data laporan berdasarkan tanggal & tipe.
     * GET params: start_date, end_date, report_type = [losses|recovery]
     */
    public function fetchReportIdentifikasiTanaman()
    {
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');
        $type  = $this->request->getGet('report_type');

        $m = new \App\Models\ReportIdentifikasiTanamanModel();
        $data = [];

        switch ($type) {
            case 'losses_estate':
                $data = $m->getLossesByEstate($start, $end);
                break;
            case 'losses_block':
                $data = $m->getLossesByBlock($start, $end);
                break;
            case 'losses_titik':
                $data = $m->getLossesByTitik($start, $end);
                break;
            case 'recovery_estate':
                $data = $m->getRecoveryByEstate($start, $end);
                break;
            case 'recovery_block':
                $data = $m->getRecoveryByBlock($start, $end);
                break;
            case 'recovery_titik':
                $data = $m->getRecoveryByTitik($start, $end);
                break;
        }

        return $this->response->setJSON($data);
    }



    public function downloadPanenBulananExcel()
    {
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');
        $type  = $this->request->getGet('report_type');

        $m = new \App\Models\ReportPanenModel();
        // Pilih query sesuai tipe
        switch ($type) {
            case 'pokok':
                $raw = $m->getAggregatedDataPokok($start, $end);
                break;
            case 'weight':
                $raw = $m->getAggregatedDataWeight($start, $end);
                break;
            case 'status':
                $raw = $m->getAggregatedDataByStatus($start, $end);
                break;
            case 'blok_status':
                $raw = $m->getAggregatedDataByBlockStatus($start, $end);
                break;
            default:
                $raw = [];
        }

        // Susun baris CSV sesuai tipe
        $rows = [];
        foreach ($raw as $d) {
            switch ($type) {
                case 'pokok':
                    $rows[] = [
                        'Month-Year'      => $d['tanggal'],
                        'PT'              => $d['PT'],
                        'Estate'          => $d['Estate'],
                        'Jumlah Tanaman'  => $d['jumlah_pohon'],
                    ];
                    break;

                case 'weight':
                    $avg = $d['jumlah_pohon']
                        ? round($d['Berat'] / $d['jumlah_pohon'], 2)
                        : 0;
                    $rows[] = [
                        'Month-Year'            => $d['tanggal'],
                        'PT'                    => $d['PT'],
                        'Estate'                => $d['Estate'],
                        'Jumlah Tanaman'        => $d['jumlah_pohon'],
                        'Total Weight (kg)'     => $d['Berat'],
                        'Average Tandan (kg)'   => $avg,
                    ];
                    break;

                case 'status':
                    $rows[] = [
                        'Month-Year'                  => $d['tanggal'],
                        'PT'                          => $d['PT'],
                        'Estate'                      => $d['Estate'],
                        'Status'                      => $d['Status'],
                        'Jumlah Tanaman Per Status'   => $d['jumlah_pohon'],
                    ];
                    break;

                case 'blok_status':
                    $rows[] = [
                        'Month-Year'                  => $d['tanggal'],
                        'PT'                          => $d['PT'],
                        'Estate'                      => $d['Estate'],
                        'Blok'                        => $d['Block'],
                        'Status'                      => $d['Status'],
                        'Jumlah Tanaman Per Status'   => $d['jumlah_pohon'],
                    ];
                    break;
            }
        }

        // streaming CSV
        $filename = "laporan-panen-bulanan_{$type}_" . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        if (!empty($rows)) {
            fputcsv($out, array_keys($rows[0]));
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
        }
        fclose($out);
        exit;
    }

    public function downloadPanenBulananPdf()
    {
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');
        $type  = $this->request->getGet('report_type');

        $m = new \App\Models\ReportPanenModel();
        switch ($type) {
            case 'pokok':
                $raw = $m->getAggregatedDataPokok($start, $end);
                break;
            case 'weight':
                $raw = $m->getAggregatedDataWeight($start, $end);
                break;
            case 'status':
                $raw = $m->getAggregatedDataByStatus($start, $end);
                break;
            case 'blok_status':
                $raw = $m->getAggregatedDataByBlockStatus($start, $end);
                break;
            default:
                $raw = [];
        }

        // Susun baris sama seperti Excel
        $rows = [];
        foreach ($raw as $d) {
            switch ($type) {
                case 'pokok':
                    $rows[] = [
                        'Month-Year'     => $d['tanggal'],
                        'PT'             => $d['PT'],
                        'Estate'         => $d['Estate'],
                        'Jumlah Tanaman' => $d['jumlah_pohon'],
                    ];
                    break;

                case 'weight':
                    $avg = $d['jumlah_pohon']
                        ? round($d['Berat'] / $d['jumlah_pohon'], 2)
                        : 0;
                    $rows[] = [
                        'Month-Year'           => $d['tanggal'],
                        'PT'                   => $d['PT'],
                        'Estate'               => $d['Estate'],
                        'Jumlah Tanaman'       => $d['jumlah_pohon'],
                        'Total Weight (kg)'    => $d['Berat'],
                        'Average Tandan (kg)'  => $avg,
                    ];
                    break;

                case 'status':
                    $rows[] = [
                        'Month-Year'                => $d['tanggal'],
                        'PT'                        => $d['PT'],
                        'Estate'                    => $d['Estate'],
                        'Status'                    => $d['Status'],
                        'Jumlah Tanaman Per Status' => $d['jumlah_pohon'],
                    ];
                    break;

                case 'blok_status':
                    $rows[] = [
                        'Month-Year'                => $d['tanggal'],
                        'PT'                        => $d['PT'],
                        'Estate'                    => $d['Estate'],
                        'Blok'                      => $d['Block'],
                        'Status'                    => $d['Status'],
                        'Jumlah Tanaman Per Status' => $d['jumlah_pohon'],
                    ];
                    break;
            }
        }

        // Buat PDF
        $pdf = new \Fpdf\Fpdf('L', 'mm', 'A4');
        $pdf->SetMargins(10, 10);
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Panen Bulanan', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, "Periode: " . date('d-m-Y', strtotime($start)) . " s/d " . date('d-m-Y', strtotime($end)), 0, 1, 'C');
        $pdf->Ln(4);

        if (empty($rows)) {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, 'Tidak ada data untuk periode ini.', 1, 1, 'C');
        } else {
            $header = array_keys($rows[0]);
            $usableW = $pdf->GetPageWidth() - 20;
            $colW = $usableW / count($header);

            // Header table
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetFillColor(230, 230, 230);
            foreach ($header as $col) {
                $pdf->Cell($colW, 7, $col, 1, 0, 'C', true);
            }
            $pdf->Ln();

            // Data rows
            $pdf->SetFont('Arial', '', 9);
            foreach ($rows as $row) {
                foreach ($row as $cell) {
                    $pdf->Cell($colW, 6, $cell, 1);
                }
                $pdf->Ln();
            }
        }

        $filename = "laporan-panen-bulanan_{$type}_" . date('Ymd_His') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }

    /**
     * Download Laporan Identifikasi Tanaman sebagai CSV (Excel)
     */
    public function downloadIdentifikasiTanamanExcel()
    {
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');
        $type  = $this->request->getGet('report_type');

        $model = new ReportIdentifikasiTanamanModel();

        // Pilih data sesuai tipe
        switch ($type) {
            case 'losses_estate':
                $raw = $model->getLossesByEstate($start, $end);
                break;
            case 'losses_block':
                $raw = $model->getLossesByBlock($start, $end);
                break;
            case 'losses_titik':
                $raw = $model->getLossesByTitik($start, $end);
                break;
            case 'recovery_estate':
                $raw = $model->getRecoveryByEstate($start, $end);
                break;
            case 'recovery_block':
                $raw = $model->getRecoveryByBlock($start, $end);
                break;
            case 'recovery_titik':
                $raw = $model->getRecoveryByTitik($start, $end);
                break;
            default:
                $raw = [];
        }

        // Susun baris CSV sesuai tipe
        $rows = [];
        foreach ($raw as $d) {
            switch ($type) {
                case 'losses_estate':
                    $rows[] = [
                        'PT'             => $d['pt'],
                        'Estate'         => $d['estate'],
                        'Jumlah Losses'  => $d['jumlah_losses'],
                    ];
                    break;
                case 'losses_block':
                    $rows[] = [
                        'PT'             => $d['pt'],
                        'Estate'         => $d['estate'],
                        'Blok'           => $d['nama_blok'],
                        'Jumlah Losses'  => $d['jumlah_losses'],
                    ];
                    break;
                case 'losses_titik':
                    $rows[] = [
                        'PT'             => $d['pt'],
                        'Estate'         => $d['estate'],
                        'Blok'           => $d['nama_blok'],
                        'Titik Tanam'    => $d['no_titik_tanam'],
                        'Jumlah Losses'  => $d['jumlah_losses'],
                    ];
                    break;
                case 'recovery_estate':
                    $rows[] = [
                        'PT'                => $d['pt'],
                        'Estate'            => $d['estate'],
                        'Jumlah Recovery'   => $d['jumlah_recovery'],
                    ];
                    break;
                case 'recovery_block':
                    $rows[] = [
                        'PT'                => $d['pt'],
                        'Estate'            => $d['estate'],
                        'Blok'              => $d['nama_blok'],
                        'Jumlah Recovery'   => $d['jumlah_recovery'],
                    ];
                    break;
                case 'recovery_titik':
                    $rows[] = [
                        'PT'                => $d['pt'],
                        'Estate'            => $d['estate'],
                        'Blok'              => $d['nama_blok'],
                        'Titik Tanam'       => $d['no_titik_tanam'],
                        'Jumlah Recovery'   => $d['jumlah_recovery'],
                    ];
                    break;
            }
        }

        // streaming CSV
        $filename = "laporan_identifikasi_{$type}_" . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        if (!empty($rows)) {
            fputcsv($out, array_keys($rows[0]));
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
        }
        fclose($out);
        exit;
    }

    /**
     * Download Laporan Identifikasi Tanaman sebagai PDF
     */
    public function downloadIdentifikasiTanamanPdf()
    {
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');
        $type  = $this->request->getGet('report_type');

        $model = new ReportIdentifikasiTanamanModel();

        // Pilih data sesuai tipe
        switch ($type) {
            case 'losses_estate':
                $raw = $model->getLossesByEstate($start, $end);
                break;
            case 'losses_block':
                $raw = $model->getLossesByBlock($start, $end);
                break;
            case 'losses_titik':
                $raw = $model->getLossesByTitik($start, $end);
                break;
            case 'recovery_estate':
                $raw = $model->getRecoveryByEstate($start, $end);
                break;
            case 'recovery_block':
                $raw = $model->getRecoveryByBlock($start, $end);
                break;
            case 'recovery_titik':
                $raw = $model->getRecoveryByTitik($start, $end);
                break;
            default:
                $raw = [];
        }

        // Susun baris sama seperti CSV
        $rows = [];
        foreach ($raw as $d) {
            switch ($type) {
                case 'losses_estate':
                    $rows[] = [
                        'PT'             => $d['pt'],
                        'Estate'         => $d['estate'],
                        'Jumlah Losses'  => $d['jumlah_losses'],
                    ];
                    break;
                case 'losses_block':
                    $rows[] = [
                        'PT'             => $d['pt'],
                        'Estate'         => $d['estate'],
                        'Blok'           => $d['nama_blok'],
                        'Jumlah Losses'  => $d['jumlah_losses'],
                    ];
                    break;
                case 'losses_titik':
                    $rows[] = [
                        'PT'             => $d['pt'],
                        'Estate'         => $d['estate'],
                        'Blok'           => $d['nama_blok'],
                        'Titik Tanam'    => $d['no_titik_tanam'],
                        'Jumlah Losses'  => $d['jumlah_losses'],
                    ];
                    break;
                case 'recovery_estate':
                    $rows[] = [
                        'PT'                => $d['pt'],
                        'Estate'            => $d['estate'],
                        'Jumlah Recovery'   => $d['jumlah_recovery'],
                    ];
                    break;
                case 'recovery_block':
                    $rows[] = [
                        'PT'                => $d['pt'],
                        'Estate'            => $d['estate'],
                        'Blok'              => $d['nama_blok'],
                        'Jumlah Recovery'   => $d['jumlah_recovery'],
                    ];
                    break;
                case 'recovery_titik':
                    $rows[] = [
                        'PT'                => $d['pt'],
                        'Estate'            => $d['estate'],
                        'Blok'              => $d['nama_blok'],
                        'Titik Tanam'       => $d['no_titik_tanam'],
                        'Jumlah Recovery'   => $d['jumlah_recovery'],
                    ];
                    break;
            }
        }

        // Buat PDF
        $pdf = new Fpdf('L', 'mm', 'A4');
        $pdf->SetMargins(10, 10);
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Identifikasi Tanaman', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, "Periode: " . date('d-m-Y', strtotime($start)) . " s/d " . date('d-m-Y', strtotime($end)), 0, 1, 'C');
        $pdf->Ln(4);

        if (empty($rows)) {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, 'Tidak ada data untuk periode ini.', 1, 1, 'C');
        } else {
            // Header table
            $header = array_keys($rows[0]);
            $usableW = $pdf->GetPageWidth() - 20;
            $colW = $usableW / count($header);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetFillColor(230, 230, 230);
            foreach ($header as $col) {
                $pdf->Cell($colW, 7, $col, 1, 0, 'C', true);
            }
            $pdf->Ln();

            // Data rows
            $pdf->SetFont('Arial', '', 9);
            foreach ($rows as $row) {
                foreach ($row as $cell) {
                    $pdf->Cell($colW, 6, $cell, 1);
                }
                $pdf->Ln();
            }
        }

        $filename = "laporan_identifikasi_{$type}_" . date('Ymd_His') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }

    /**
     * Download Prediksi Panen as CSV (for Excel)
     */
    public function downloadPrediksiPanenExcel()
    {
        // 1) Read GET params
        $ptEstate = $this->request->getGet('pt_estate');
        $blok     = $this->request->getGet('blok_id');
        $policyId = $this->request->getGet('policy_id');

        // 2) Validate inputs (replaces deprecated failValidationError)
        if (! $ptEstate || ! $blok || ! $policyId) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'pt_estate, blok_id, and policy_id are required']);
        }

        // 3) Load HS and Policy (replaces failNotFound)
        $hs = (new HectareStatementModel())
            ->getHectareStatementByPtEstateIdAndBlockId($ptEstate, $blok);
        if (! $hs) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Hectare statement not found']);
        }
        $policy = (new PolicyModel())->find($policyId);
        if (! $policy) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Policy not found']);
        }

        // 4) Fetch tanaman & status map
        $tanamans  = (new TanamanModel())
            ->where('hs_id', $hs['hs_id'])
            ->where('tgl_akhir_identifikasi', null)
            ->findAll();
        $statusMap = array_column((new StatusModel())->findAll(), 'nama_status', 'status_id');

        // 5) Build rows
        $now       = new \DateTime();
        $startDate = $policy['start'] !== '0000-00-00 00:00:00'
            ? new \DateTime($policy['start'])
            : null;
        $endDate   = $policy['akhir'] !== '0000-00-00 00:00:00'
            ? new \DateTime($policy['akhir'])
            : null;

        $rows = [];
        foreach ($tanamans as $t) {
            $tglIdent         = new \DateTime($t['tgl_mulai_identifikasi']);
            $weeksSinceIdent  = floor($now->diff($tglIdent)->days / 7);
            $currentAge       = (int)$t['minggu'] + $weeksSinceIdent;

            // threshold logic
            if ($endDate && $tglIdent > $endDate) {
                $threshold = (int)$policy['lama'];
            } elseif ($startDate && $tglIdent > $startDate) {
                $threshold = (int)$policy['baru'];
            } else {
                $threshold = (int)$policy['lama'];
            }
            if ($threshold <= 0 || $currentAge < $threshold - 1) {
                continue;
            }

            $rows[] = [
                'PT'                  => $hs['pt'],
                'Estate'              => $hs['estate'],
                'Blok'                => $hs['nama_blok'] ?? $hs['blok_id'],
                'TanggalIdentifikasi' => $t['tgl_mulai_identifikasi'],
                'RFIDTanaman'         => $t['rfid_tanaman'],
                'NoTitikTanam'        => $t['no_titik_tanam'],
                'Longitude'           => $t['longitude_tanam'],
                'Latitude'            => $t['latitude_tanam'],
                'Status'              => $statusMap[$t['status_id']] ?? '-',
                'Sister'              => $t['sister'],
                'Umur (minggu)'       => $currentAge,
            ];
        }

        // 6) Stream CSV
        $filename = 'laporan-prediksi-panen_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        if (! empty($rows)) {
            fputcsv($out, array_keys($rows[0]));
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
        }
        fclose($out);
        exit;
    }

    /**
     * Download ALL Prediksi Panen as CSV (for Excel)
     */
    public function downloadAllPrediksiPanenExcel()
    {
        // 1) Fetch all Tanaman where tgl_akhir_identifikasi IS NULL
        $tanamanModel = new \App\Models\TanamanModel();
        $tanamans     = $tanamanModel
            ->where('tgl_akhir_identifikasi', null)
            ->findAll();

        // 2) Build map of status_id => nama_status
        $statusMap = array_column(
            (new \App\Models\StatusModel())->findAll(),
            'nama_status',
            'status_id'
        );

        // 3) We'll need hectare_statement for each record
        $hsModel = new \App\Models\HectareStatementModel();

        $now  = new \DateTime();
        $rows = [];

        foreach ($tanamans as $t) {
            // join to get pt, estate, nama_blok
            $hs = $hsModel
                ->select('hectare_statement.*, master_blok.nama_blok, pt_estate.pt, pt_estate.estate')
                ->join('master_blok', 'master_blok.blok_id = hectare_statement.blok_id', 'left')
                ->join('pt_estate',  'pt_estate.pt_estate_id = hectare_statement.pt_estate_id', 'left')
                ->where('hs_id', $t['hs_id'])
                ->first();
            if (! $hs) continue;

            // compute umur in weeks
            $tglIdent       = new \DateTime($t['tgl_mulai_identifikasi']);
            $weeksSince     = floor($now->diff($tglIdent)->days / 7);
            $currentAge     = (int)$t['minggu'] + $weeksSince;

            $rows[] = [
                'PT'                    => $hs['pt']               ?? '-',
                'Estate'                => $hs['estate']           ?? '-',
                'Blok'                  => $hs['nama_blok']        ?? $hs['blok_id'],
                'TanggalIdentifikasi'   => $t['tgl_mulai_identifikasi'],
                'RFIDTanaman'           => $t['rfid_tanaman'],
                'NoTitikTanam'          => $t['no_titik_tanam'],
                'Longitude'             => $t['longitude_tanam'],
                'Latitude'              => $t['latitude_tanam'],
                'Status'                => $statusMap[$t['status_id']] ?? '-',
                'Sister'                => $t['sister'],
                'Umur (minggu)'         => $currentAge,
            ];
        }

        // 4) Stream CSV to browser
        $filename = 'prediksi-panen-all_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        if (! empty($rows)) {
            // header row
            fputcsv($out, array_keys($rows[0]));
            // data rows
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
        }
        fclose($out);
        exit;
    }

    /**
     * Download Prediksi Panen as PDF via FPDF
     */
    public function downloadPrediksiPanenPdf()
    {
        // 1) Read & validate GET params
        $ptEstate = $this->request->getGet('pt_estate');
        $blok     = $this->request->getGet('blok_id');
        $policyId = $this->request->getGet('policy_id');
        if (! $ptEstate || ! $blok || ! $policyId) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'pt_estate, blok_id, and policy_id are required']);
        }

        // 2) Load HS & Policy
        $hs = (new HectareStatementModel())
            ->getHectareStatementByPtEstateIdAndBlockId($ptEstate, $blok);
        if (! $hs) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Hectare statement not found']);
        }
        $policy = (new PolicyModel())->find($policyId);
        if (! $policy) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Policy not found']);
        }

        // 3) Fetch tanaman & status map
        $tanamans  = (new TanamanModel())
            ->where('hs_id', $hs['hs_id'])
            ->where('tgl_akhir_identifikasi', null)
            ->findAll();
        $statusMap = array_column(
            (new StatusModel())->findAll(),
            'nama_status',
            'status_id'
        );

        // 4) Build data rows
        $now       = new \DateTime();
        $startDate = $policy['start'] !== '0000-00-00 00:00:00'
            ? new \DateTime($policy['start'])
            : null;
        $endDate   = $policy['akhir'] !== '0000-00-00 00:00:00'
            ? new \DateTime($policy['akhir'])
            : null;

        $rows = [];
        foreach ($tanamans as $t) {
            $tglIdent    = new \DateTime($t['tgl_mulai_identifikasi']);
            $weeksSince  = floor($now->diff($tglIdent)->days / 7);
            $currentAge  = (int)$t['minggu'] + $weeksSince;

            // threshold logic
            if ($endDate && $tglIdent > $endDate) {
                $threshold = (int)$policy['lama'];
            } elseif ($startDate && $tglIdent > $startDate) {
                $threshold = (int)$policy['baru'];
            } else {
                $threshold = (int)$policy['lama'];
            }
            if ($threshold <= 0 || $currentAge < $threshold - 1) {
                continue;
            }

            $rows[] = [
                'PT'                    => $hs['pt'],
                'Estate'                => $hs['estate'],
                'Blok'                  => $hs['nama_blok'] ?? $hs['blok_id'],
                'TanggalIdentifikasi'   => $t['tgl_mulai_identifikasi'],
                'RFIDTanaman'           => $t['rfid_tanaman'],
                'NoTitikTanam'          => $t['no_titik_tanam'],
                'Longitude'             => $t['longitude_tanam'],
                'Latitude'              => $t['latitude_tanam'],
                'Status'                => $statusMap[$t['status_id']] ?? '-',
                'Sister'                => $t['sister'],
                'Umur (minggu)'         => $currentAge,
            ];
        }

        // 5) PDF setup
        $pdf    = new Fpdf('L', 'mm', 'A4');
        $margin = 8;
        $pdf->SetMargins($margin, $margin);
        $pdf->AddPage();

        // Title (centered)
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 8, 'Laporan Prediksi Panen', 0, 1, 'C');
        $pdf->Ln(4);

        if (empty($rows)) {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 8, 'Tidak ada data untuk kriteria ini.', 1, 1, 'C');
        } else {
            // 6) Compute dynamic column widths
            $header   = array_keys($rows[0]);
            $colCount = count($header);
            $usableW  = $pdf->GetPageWidth() - 2 * $margin;
            $cellPad  = 4;
            $widths   = array_fill(0, $colCount, 0);

            // measure header
            $pdf->SetFont('Arial', 'B', 9);
            foreach ($header as $i => $col) {
                $w = $pdf->GetStringWidth($col) + $cellPad;
                if ($w > $widths[$i]) $widths[$i] = $w;
            }
            // measure content
            $pdf->SetFont('Arial', '', 8);
            foreach ($rows as $row) {
                foreach (array_values($row) as $i => $cell) {
                    $w = $pdf->GetStringWidth((string)$cell) + $cellPad;
                    if ($w > $widths[$i]) $widths[$i] = $w;
                }
            }
            // scale to fit
            $totalW = array_sum($widths);
            if ($totalW > $usableW) {
                $scale = $usableW / $totalW;
                foreach ($widths as &$w) {
                    $w *= $scale;
                }
                unset($w);
                $totalW = $usableW;
            }

            // 7) Compute starting X to center the table
            $startX = ($pdf->GetPageWidth() - $totalW) / 2;

            // 8) Draw header row
            $pdf->SetFillColor(230, 230, 230);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetX($startX);
            foreach ($header as $i => $col) {
                $pdf->Cell($widths[$i], 7, $col, 1, 0, 'C', true);
            }
            $pdf->Ln();

            // 9) Draw data rows
            $pdf->SetFont('Arial', '', 8);
            foreach ($rows as $row) {
                $pdf->SetX($startX);
                foreach (array_values($row) as $i => $cell) {
                    $pdf->Cell($widths[$i], 6, $cell, 1);
                }
                $pdf->Ln();
            }
        }

        $pdf->Output('D', 'laporan-prediksi-panen_' . date('Ymd_His') . '.pdf');
        exit;
    }


    /**
     * Download ALL Prediksi Panen as PDF via FPDF
     */
    public function downloadAllPrediksiPanenPdf()
    {
        // 1) Fetch all tanaman
        $tanamans  = (new TanamanModel())
            ->where('tgl_akhir_identifikasi', null)
            ->findAll();
        $statusMap = array_column(
            (new StatusModel())->findAll(),
            'nama_status',
            'status_id'
        );
        $hsModel   = new HectareStatementModel();

        // 2) Build rows
        $now  = new \DateTime();
        $rows = [];
        foreach ($tanamans as $t) {
            $hs = $hsModel
                ->select('hectare_statement.*, master_blok.nama_blok, pt_estate.pt, pt_estate.estate')
                ->join('master_blok', 'master_blok.blok_id = hectare_statement.blok_id', 'left')
                ->join('pt_estate',  'pt_estate.pt_estate_id = hectare_statement.pt_estate_id', 'left')
                ->where('hs_id', $t['hs_id'])
                ->first();
            if (! $hs) continue;

            $tglIdent   = new \DateTime($t['tgl_mulai_identifikasi']);
            $weeksSince = floor($now->diff($tglIdent)->days / 7);
            $currentAge = (int)$t['minggu'] + $weeksSince;

            $rows[] = [
                'PT'                    => $hs['pt']               ?? '-',
                'Estate'                => $hs['estate']           ?? '-',
                'Blok'                  => $hs['nama_blok']        ?? $hs['blok_id'],
                'TanggalIdentifikasi'   => $t['tgl_mulai_identifikasi'],
                'RFIDTanaman'           => $t['rfid_tanaman'],
                'NoTitikTanam'          => $t['no_titik_tanam'],
                'Longitude'             => $t['longitude_tanam'],
                'Latitude'              => $t['latitude_tanam'],
                'Status'                => $statusMap[$t['status_id']] ?? '-',
                'Sister'                => $t['sister'],
                'Umur (minggu)'         => $currentAge,
            ];
        }

        // 3) PDF setup
        $pdf    = new Fpdf('L', 'mm', 'A4');
        $margin = 8;
        $pdf->SetMargins($margin, $margin);
        $pdf->AddPage();

        // Title (center)
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 8, 'Prediksi Panen - Semua Data', 0, 1, 'C');
        $pdf->Ln(4);

        if (empty($rows)) {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 8, 'Tidak ada data.', 1, 1, 'C');
        } else {
            // 4) Dynamic widths
            $header   = array_keys($rows[0]);
            $colCount = count($header);
            $usableW  = $pdf->GetPageWidth() - 2 * $margin;
            $cellPad  = 4;
            $widths   = array_fill(0, $colCount, 0);

            // measure header
            $pdf->SetFont('Arial', 'B', 9);
            foreach ($header as $i => $col) {
                $w = $pdf->GetStringWidth($col) + $cellPad;
                if ($w > $widths[$i]) $widths[$i] = $w;
            }
            // measure content
            $pdf->SetFont('Arial', '', 8);
            foreach ($rows as $row) {
                foreach (array_values($row) as $i => $cell) {
                    $w = $pdf->GetStringWidth((string)$cell) + $cellPad;
                    if ($w > $widths[$i]) $widths[$i] = $w;
                }
            }
            // scale to fit
            $totalW = array_sum($widths);
            if ($totalW > $usableW) {
                $scale = $usableW / $totalW;
                foreach ($widths as &$w) {
                    $w *= $scale;
                }
                unset($w);
                $totalW = $usableW;
            }

            // 5) Compute startX for centering
            $startX = ($pdf->GetPageWidth() - $totalW) / 2;

            // 6) Draw header
            $pdf->SetFillColor(230, 230, 230);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetX($startX);
            foreach ($header as $i => $col) {
                $pdf->Cell($widths[$i], 7, $col, 1, 0, 'C', true);
            }
            $pdf->Ln();

            // 7) Draw data rows
            $pdf->SetFont('Arial', '', 8);
            foreach ($rows as $row) {
                $pdf->SetX($startX);
                foreach (array_values($row) as $i => $cell) {
                    $pdf->Cell($widths[$i], 6, $cell, 1);
                }
                $pdf->Ln();
            }
        }

        $pdf->Output('D', 'prediksi-panen-all_' . date('Ymd_His') . '.pdf');
        exit;
    }
}
