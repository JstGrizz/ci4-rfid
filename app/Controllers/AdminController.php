<?php

namespace App\Controllers;

use App\Models\KaryawanModel;
use App\Models\ReportPanenModel;
use App\Models\TanamanModel;
use App\Controllers\BaseController;

class AdminController extends BaseController
{
    public function index()
    {
        // 1. Cek session login
        $session = session();
        $npk     = $session->get('npk');

        if (! $session->get('isLoggedIn') || ! $npk) {
            return redirect()->to('/login');
        }

        // 2. Ambil data karyawan (sesuai npk)
        $karyawanModel = new KaryawanModel();
        $karyawan      = $karyawanModel->getKaryawanNameWithNpk($npk);
        if (! $karyawan) {
            $session->remove('npk');
            $session->remove('isLoggedIn');
            return redirect()->to('/login');
        }

        // 3. Hitung “jumlah karyawan”
        $jumlahKaryawan = $karyawanModel->countAll();

        // 4. Hitung “jumlah tanaman aktif”
        $tanamanModel = new TanamanModel();
        $jumlahTanamanAktif = $tanamanModel
            ->where('tgl_akhir_identifikasi', null)
            ->countAllResults();

        // 5. Hitung “tanaman yang telah panen di tahun ini”
        $currentYear         = date('Y');
        $reportPanenModel    = new ReportPanenModel();
        $jumlahPanenThisYear = $reportPanenModel
            ->where("YEAR(tgl_transaksi)", $currentYear)
            ->countAllResults();

        // 6. Hitung “panen per bulan” (untuk chart)
        $builderPanen = $reportPanenModel->builder();
        $builderPanen
            ->select("MONTH(tgl_transaksi) AS bulan, COUNT(*) AS total")
            ->where("YEAR(tgl_transaksi)", $currentYear)
            ->groupBy("MONTH(tgl_transaksi)");
        $resultsPanen = $builderPanen->get()->getResultArray();

        $countsPanenPerMonth = array_fill(1, 12, 0);
        foreach ($resultsPanen as $row) {
            $bulan = (int) $row['bulan'];
            $countsPanenPerMonth[$bulan] = (int) $row['total'];
        }

        $panenCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $panenCounts[] = $countsPanenPerMonth[$i];
        }

        // 7. Label bulan (untuk chart)
        $panenLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // 8. Kirim semua data ke view (tanpa history losses)
        return view('admin/index', [
            'karyawan'            => $karyawan,
            'jumlahKaryawan'      => $jumlahKaryawan,
            'jumlahTanamanAktif'  => $jumlahTanamanAktif,
            'jumlahPanenThisYear' => $jumlahPanenThisYear,
            'panenCounts'         => $panenCounts,
            'panenLabels'         => $panenLabels,
            'currentYear'         => $currentYear,
        ]);
    }
}
