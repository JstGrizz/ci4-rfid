<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\KaryawanModel;
use App\Models\PtEstateModel;
use App\Models\MasterBlokModel;
use App\Models\HectareStatementModel;
use App\Models\TanamanModel;
use App\Models\StatusModel;
use App\Models\MasterLossesModel;

class IdentifikasiTanamanController extends ResourceController
{
    public function new()
    {
        $session = session();
        $npk = $session->get('npk'); // Ambil NPK dari session

        $karyawanModel = new KaryawanModel();
        // Ambil data Karyawan menggunakan NPK
        $karyawan = $karyawanModel->getKaryawanNameWithNpk($npk);

        // Inisialisasi data array
        $data = [];

        if ($karyawan) {
            $data['npk'] = $npk; // Pass npk ke view
            $data['nama'] = $karyawan['nama'];
        } else {
            $data['npk'] = '';
            $data['nama'] = '';
        }

        $hectareStatementModel = new HectareStatementModel();
        $blokModel = new MasterBlokModel();

        // Ambil daftar PT dan Estate unik dari hectare_statement
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
                $data['bloks'] = $blokModel->getBloksInHectareStatement($ptEstateId);

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

        // Load view dan pass data
        return view('identifikasi-tanaman-new', $data);
    }

    public function viewEdit()
    {
        $session = session();
        $npk = $session->get('npk'); // Ambil NPK dari session

        $karyawanModel = new KaryawanModel();
        // Ambil data Karyawan menggunakan NPK
        $karyawan = $karyawanModel->getKaryawanNameWithNpk($npk);

        // Inisialisasi data array
        $data = [];

        if ($karyawan) {
            $data['npk'] = $npk; // Pass npk ke view
            $data['nama'] = $karyawan['nama'];
        } else {
            $data['npk'] = '';
            $data['nama'] = '';
        }

        $hectareStatementModel = new HectareStatementModel();
        $blokModel = new MasterBlokModel();

        // Ambil daftar PT dan Estate unik dari hectare_statement
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
                $data['bloks'] = $blokModel->getBloksInHectareStatement($ptEstateId);

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

        // Load view dan pass data
        return view('identifikasi-tanaman-update', $data);
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

    public function getNoTitikTanamData($noTitikTanam, $ptEstateId, $blokId)
    {
        // First, get the hs_id based on pt_estate_id and blok_id
        $hsId = $this->getHsIdByPtEstateAndBlok($ptEstateId, $blokId);

        if ($hsId) {
            // If hs_id found, pass both no_titik_tanam and hs_id to the model
            $TanamanModel = new TanamanModel();
            $data = $TanamanModel->getNoTitikTanamData($noTitikTanam, $hsId);

            if ($data) {
                return $this->response->setJSON([
                    'latitude' => $data['latitude_tanam'],
                    'longitude' => $data['longitude_tanam'],
                    'found' => true
                ]);
            }
        }

        // If no data found or hs_id is null, return the 'found' as false
        return $this->response->setJSON(['found' => false]);
    }

    public function getTanamanStatus($noTitikTanam, $ptEstateId, $blokId, $longitudeTanam, $latitudeTanam)
    {
        $hsId = $this->getHsIdByPtEstateAndBlok($ptEstateId, $blokId);

        if ($hsId === null) {
            return $this->response->setJSON(['success' => false, 'error' => 'Pernyataan Hektar tidak ditemukan.']);
        }

        $tanamanModel = new TanamanModel();
        $latestStatusID = $tanamanModel->fetchLatestStatusForTitikTanam(
            $longitudeTanam,
            $latitudeTanam,
            $noTitikTanam,
            $hsId
        );

        if ($latestStatusID !== null) {
            $statusModel = new StatusModel();
            $isActive = $tanamanModel->checkIfStatusIsActive(
                $longitudeTanam,
                $latitudeTanam,
                $noTitikTanam,
                $hsId,
                $latestStatusID
            );

            $statusOptions = [];
            if ($isActive) {
                $statusOptions[] = ['value' => $latestStatusID, 'label' => $statusModel->find($latestStatusID)['nama_status']];
            } else {
                $nextStatus = $statusModel->determineNextStatus($latestStatusID);
                $statusOptions[] = $nextStatus;
            }

            return $this->response->setJSON(['success' => true, 'statusOptions' => $statusOptions]);
        } else {
            $statusModel = new StatusModel();
            $defaultStatus = $statusModel->where('nama_status', 'PC')
                ->orWhere('nama_status', 'pc')
                ->first();

            if ($defaultStatus) {
                return $this->response->setJSON([
                    'success' => true,
                    'statusOptions' => [
                        ['value' => $defaultStatus['status_id'], 'label' => $defaultStatus['nama_status']]
                    ]
                ]);
            }
        }

        return $this->response->setJSON(['success' => false, 'error' => 'Status tidak ditemukan.']);
    }

    public function fetchSister($latitude = null, $longitude = null, $noTitikTanam = null)
    {
        $ptEstateId = $this->request->getGet('ptEstateId');
        $blokId = $this->request->getGet('blokId');

        if ($latitude && $longitude && $noTitikTanam && $ptEstateId && $blokId) {
            $hsId = $this->getHsIdByPtEstateAndBlok($ptEstateId, $blokId);

            if ($hsId === null) {
                return $this->response->setJSON(['success' => false, 'error' => 'Pernyataan Hektar tidak ditemukan.']);
            }

            $tanamanModel = new TanamanModel();
            $status = $tanamanModel->fetchLatestSisterForTitikTanam(
                $latitude,
                $longitude,
                $noTitikTanam,
                $hsId
            );

            if ($status['active_count'] > 0) {
                $nextSister = $status['max_sister'] + 1;
                return $this->response->setJSON(['success' => true, 'sister' => $nextSister]);
            } else {
                return $this->response->setJSON(['success' => true, 'sister' => 0]);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Parameter tidak lengkap.']);
        }
    }

    private function calculateWeek($tanggalTanam)
    {
        $tanggalTanam = new \DateTime($tanggalTanam);
        $today = new \DateTime();
        $interval = $today->diff($tanggalTanam);
        return floor($interval->days / 7);
    }

    public function getHsIdByPtEstateAndBlok($ptEstateId, $blokId)
    {
        $hectareStatementModel = new HectareStatementModel();
        $hectareStatement = $hectareStatementModel
            ->where('pt_estate_id', $ptEstateId)
            ->where('blok_id', $blokId)
            ->first();  // Ambil yang pertama ditemukan

        if ($hectareStatement) {
            return $hectareStatement['hs_id'];
        }

        return null;  // Kembalikan null jika tidak ada record yang ditemukan
    }

    public function insertTanamanData()
    {
        // Ambil data form dari request POST
        $pt_estate_id = $this->request->getPost('pt_estate');
        $blok_id = $this->request->getPost('blok_id');
        $rfid_tanaman = $this->request->getPost('rfid');
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        $no_titik_tanam = $this->request->getPost('no_titik_tanam');
        $status_id = $this->request->getPost('status');
        $sister = $this->request->getPost('sister_ke');
        $week = $this->request->getPost('week');
        $nama_karyawan = $this->request->getPost('nama');
        $npk = $this->request->getPost('npk');

        // Ambil hs_id berdasarkan pt_estate_id dan blok_id
        $hs_id = $this->getHsIdByPtEstateAndBlok($pt_estate_id, $blok_id);

        if (!$hs_id) {
            return $this->response->setJSON(['success' => false, 'error' => 'ID Pernyataan Hektar tidak ditemukan.']);
        }

        // Cek apakah RFID sudah ada di database (hanya jika RFID bukan NULL atau kosong)
        if (
            $rfid_tanaman !== null && $rfid_tanaman !== ""
        ) {
            $tanamanModel = new TanamanModel();
            $existingRfid = $tanamanModel
                ->where('rfid_tanaman', $rfid_tanaman)
                ->where('tgl_akhir_identifikasi', null)
                ->first();

            if ($existingRfid) {
                // Tangani kasus NULL atau kosong secara khusus
                $rfidDisplay = ($rfid_tanaman === null) ? 'NULL' : $rfid_tanaman;
                return $this->response->setJSON(['success' => false, 'error' => 'RFID ' . $rfidDisplay . ' sudah terdaftar di tanaman yang aktif, tolong diganti.']);
            }
        }

        // Siapkan data untuk dimasukkan ke dalam tabel 'tanaman'
        $data = [
            'tgl_mulai_identifikasi' => date('Y-m-d H:i:s'),
            'hs_id' => $hs_id,
            'rfid_tanaman' => $rfid_tanaman,
            'latitude_tanam' => (float)$latitude,
            'longitude_tanam' => (float)$longitude,
            'no_titik_tanam' => (int)$no_titik_tanam,
            'status_id' => (int)$status_id,
            'sister' => (int)$sister,
            'is_loses' => 'N',  // Nilai default
            'losses_id' => null,  // Nilai default
            'deskripsi_Loses' => null,  // Nilai default
            'tgl_akhir_identifikasi' => null,  // Nilai default
            'minggu' => (int)$week,
            'nama_karyawan' => $nama_karyawan,
            'npk' => $npk
        ];

        // Insert data ke dalam tabel 'tanaman' menggunakan TanamanModel
        $tanamanModel = new TanamanModel();
        if ($tanamanModel->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil disimpan.']);
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Gagal menyimpan data.']);
        }
    }

    public function getActiveTanamanData($noTitikTanam)
    {
        $tanamanModel = new TanamanModel();

        $activeTanaman = $tanamanModel->getNoActiveTanamData($noTitikTanam);

        if ($activeTanaman) {
            return $this->response->setJSON(['success' => true, 'tanaman' => $activeTanaman]);
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Tidak ada data tanaman aktif ditemukan.']);
        }
    }

    public function getLossesOptions()
    {
        $model = new MasterLossesModel();
        $lossesData = $model->getAllMasterLosses();

        return $this->response->setJSON([
            'success' => true,
            'losses' => $lossesData
        ]);
    }

    // ... (bagian atas controller Anda) ...

    public function updateIdentifikasiTanaman()
    {
        $data = $this->request->getPost();

        $ptEstateId = $data['pt_estate'] ?? null;
        $blokId = $data['blok_id'] ?? null;
        $tanamanIds = $data['tanaman_id'] ?? [];
        $rfidTanamanArray = $data['rfid_tanaman'] ?? [];
        $newRfidArray = $data['new_rfid'] ?? [];
        $lossesIdArray = $data['penyebab_loses'] ?? [];
        $deskripsiLosesArray = $data['deskripsi_loses'] ?? [];
        $updateRfidCheckboxes = $data['update_rfid'] ?? [];
        $updateLossesCheckboxes = $data['update_losses'] ?? [];

        $hsId = $this->getHsIdByPtEstateAndBlok($ptEstateId, $blokId);

        if ($hsId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pernyataan Hektar tidak ditemukan.']);
        }

        $tanamanModel = new TanamanModel();
        $currentTime = date('Y-m-d H:i:s');

        if (!is_array($tanamanIds) || empty($tanamanIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada ID tanaman yang dikirim untuk diperbarui.']);
        }

        foreach ($tanamanIds as $index => $tanamanIdToUpdate) {
            $currentRfid = $rfidTanamanArray[$index] ?? null;

            if (empty($tanamanIdToUpdate)) {
                continue; // Lewati jika ID tanaman kosong
            }

            $tanamanData = [];

            // --- Logika untuk update RFID ---
            $rfidToUse = $currentRfid;
            $isUpdateRfidChecked = isset($updateRfidCheckboxes[$index]) && $updateRfidCheckboxes[$index] === 'on';

            if ($isUpdateRfidChecked) {
                $newRfidValue = $newRfidArray[$index] ?? '';
                if (!empty($newRfidValue)) {
                    $existingNewRfid = $tanamanModel
                        ->where('rfid_tanaman', $newRfidValue)
                        ->where('tgl_akhir_identifikasi', null)
                        ->first();

                    if ($existingNewRfid && $existingNewRfid['tanaman_id'] != $tanamanIdToUpdate) {
                        return $this->response->setJSON(['success' => false, 'message' => 'RFID ' . $newRfidValue . ' sudah terdaftar di tanaman yang aktif, tolong diganti.']);
                    }
                    $rfidToUse = $newRfidValue;
                }
            }
            $tanamanData['rfid_tanaman'] = $rfidToUse;


            // --- Logika untuk update Losses ---
            $isUpdateLossesChecked = isset($updateLossesCheckboxes[$index]) && $updateLossesCheckboxes[$index] === 'on';
            $lsId = $lossesIdArray[$index] ?? null;
            $deskripsi = $deskripsiLosesArray[$index] ?? ''; // Pastikan ini string kosong jika tidak ada input

            if ($isUpdateLossesChecked && $lsId !== null) {
                $tanamanData['is_loses'] = 'Y';
                $tanamanData['losses_id'] = $lsId;
                $tanamanData['deskripsi_loses'] = $deskripsi;
                $tanamanData['tgl_akhir_identifikasi'] = $currentTime;
                $tanamanData['status_id'] = 2; // Ganti dengan ID status 'Loses' Anda yang sebenarnya
            } else {
                // Logika untuk mereset loses jika checkbox tidak dicentang atau lsId null
                $currentTanamanStatus = $tanamanModel->find($tanamanIdToUpdate);
                if ($currentTanamanStatus && $currentTanamanStatus['is_loses'] === 'Y') {
                    $tanamanData['is_loses'] = 'N';
                    $tanamanData['losses_id'] = null;
                    $tanamanData['deskripsi_loses'] = null;
                    $tanamanData['tgl_akhir_identifikasi'] = null;
                    $tanamanData['status_id'] = 1; // Ganti dengan ID status 'Aktif' Anda yang sebenarnya
                }
            }

            $updateResult = $tanamanModel->update($tanamanIdToUpdate, $tanamanData);

            if (!$updateResult) {
                log_message('error', 'Failed to update Tanaman ID ' . $tanamanIdToUpdate . '. Errors: ' . json_encode($tanamanModel->errors()));
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }
}
