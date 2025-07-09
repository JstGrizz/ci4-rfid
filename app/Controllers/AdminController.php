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
        $session = session();
        $npk     = $session->get('npk');

        if (! $session->get('isLoggedIn') || ! $npk) {
            return redirect()->to('/login');
        }

        $karyawanModel = new KaryawanModel();
        $karyawan      = $karyawanModel->getKaryawanNameWithNpk($npk);
        if (! $karyawan) {
            $session->remove('npk');
            $session->remove('isLoggedIn');
            return redirect()->to('/login');
        }

        $tanamanModel = new TanamanModel();
        $tanamanData = $tanamanModel->getActivePlantsForAdminView();

        foreach ($tanamanData as &$row) {
            $row['umur'] = $this->calculateAgeInWeeks($row['tgl_mulai_identifikasi'], $row['minggu']);
        }

        $currentYear = date('Y');

        // Get counts using model methods
        $countSeleksi = $tanamanModel->countSeleksiForCurrentYear($currentYear);
        $countShooting = $tanamanModel->countShootingForCurrentYear($currentYear);
        $countLosses = $tanamanModel->countLossesForCurrentYear($currentYear);

        // Get monthly data
        $monthlyData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach ($months as $index => $monthName) {
            $monthNum = $index + 1;

            $monthlyData['seleksi'][$monthName] = $tanamanModel
                ->join('tipe_aktivitas', 'tipe_aktivitas.aktivitas_id = tanaman.aktivitas_id')
                ->where('YEAR(tanaman.tgl_mulai_identifikasi)', $currentYear)
                ->where('MONTH(tanaman.tgl_mulai_identifikasi)', $monthNum)
                ->where('tanaman.tgl_akhir_identifikasi IS NOT NULL')
                ->where('tanaman.losses_id IS NULL')
                ->like('LOWER(tipe_aktivitas.nama_aktivitas)', 'seleksi')
                ->countAllResults();

            $monthlyData['shooting'][$monthName] = $tanamanModel
                ->join('tipe_aktivitas', 'tipe_aktivitas.aktivitas_id = tanaman.aktivitas_id')
                ->where('YEAR(tanaman.tgl_mulai_identifikasi)', $currentYear)
                ->where('MONTH(tanaman.tgl_mulai_identifikasi)', $monthNum)
                ->where('tanaman.tgl_akhir_identifikasi IS NOT NULL')
                ->where('tanaman.losses_id IS NULL')
                ->like('LOWER(tipe_aktivitas.nama_aktivitas)', 'shooting')
                ->countAllResults();

            $monthlyData['losses'][$monthName] = $tanamanModel
                ->where('YEAR(tanaman.tgl_mulai_identifikasi)', $currentYear)
                ->where('MONTH(tanaman.tgl_mulai_identifikasi)', $monthNum)
                ->where('tanaman.tgl_akhir_identifikasi IS NOT NULL')
                ->where('tanaman.losses_id IS NOT NULL')
                ->countAllResults();
        }

        return view('admin/index', [
            'karyawan' => $karyawan,
            'tanamanData' => $tanamanData,
            'countSeleksi' => $countSeleksi,
            'countShooting' => $countShooting,
            'countLosses' => $countLosses,
            'monthlyData' => $monthlyData,
            'months' => $months
        ]);
    }

    private function calculateAgeInWeeks($startDate, $minggu)
    {
        if (!$startDate) return 0;

        $start = new \DateTime($startDate);
        $now = new \DateTime();
        $interval = $start->diff($now);
        $totalWeeks = floor($interval->days / 7);

        return max(0, $totalWeeks + $minggu);
    }
}
