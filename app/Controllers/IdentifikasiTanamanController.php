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
use App\Models\TipeAktivitasModel;

class IdentifikasiTanamanController extends ResourceController
{
    public function baru()
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
        $tipeAktivitasModel = new TipeAktivitasModel(); // Load TipeAktivitasModel

        // Ambil daftar PT dan Estate unik dari hectare_statement
        $data['ptEstates'] = $hectareStatementModel->getUniquePtEstates();
        $data['tipeAktivitas'] = $tipeAktivitasModel->getAll(); // Fetch all Tipe Aktivitas

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
        return view('identifikasi-tanaman-baru', $data);
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

    public function fetchSister()
    {
        // Grab all three params from query string:
        $noTitikTanam = $this->request->getGet('noTitikTanam');
        $ptEstateId   = $this->request->getGet('ptEstateId');
        $blokId       = $this->request->getGet('blokId');

        if (! $noTitikTanam || ! $ptEstateId || ! $blokId) {
            return $this->response->setJSON([
                'success' => false,
                'error'   => 'Parameter tidak lengkap.'
            ]);
        }

        // Lookup the HectareStatement ID (your helper method)
        $hsModel = new HectareStatementModel();
        $hs = $hsModel->getHectareStatementByPtEstateIdAndBlockId($ptEstateId, $blokId);
        if (! $hs) {
            return $this->response->setJSON([
                'success' => false,
                'error'   => 'Pernyataan Hektar tidak ditemukan.'
            ]);
        }
        $hsId = $hs['hs_id'];

        // Fetch the latest sister for these coordinates & titik
        $tanamanModel = new TanamanModel();
        $status = $tanamanModel->fetchLatestSisterForTitikTanam($noTitikTanam, $hsId);

        if ($status['active_count'] > 0) {
            $nextSister = $status['max_sister'] + 1;
        } else {
            $nextSister = 0;
        }

        return $this->response->setJSON([
            'success' => true,
            'sister'  => $nextSister
        ]);
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

    public function insertTanamanDataSeleksi()
    {
        // Ambil data form dari request POST
        $pt_estate_id = $this->request->getPost('pt_estate');
        $blok_id = $this->request->getPost('blok_id');
        $rfid_tanaman = $this->request->getPost('rfid');
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        $no_titik_tanam = $this->request->getPost('no_titik_tanam');
        $status_id = $this->request->getPost('status');  // Get status_id from frontend
        $sister = $this->request->getPost('sister_ke');
        $week = $this->request->getPost('week'); // Week from the form
        $nama_karyawan = $this->request->getPost('nama');
        $npk = $this->request->getPost('npk');
        $aktivitas_id = $this->request->getPost('tipe_aktivitas'); // Get aktivitas_id from frontend

        // Ambil hs_id berdasarkan pt_estate_id dan blok_id
        $hs_id = $this->getHsIdByPtEstateAndBlok($pt_estate_id, $blok_id);

        if (!$hs_id) {
            return $this->response->setJSON(['success' => false, 'error' => 'ID Pernyataan Hektar tidak ditemukan.']);
        }

        // Cek apakah RFID sudah ada di database (hanya jika RFID bukan NULL atau kosong)
        if ($rfid_tanaman !== null && $rfid_tanaman !== "") {
            $tanamanModel = new TanamanModel();
            $existingRfid = $tanamanModel
                ->where('rfid_tanaman', $rfid_tanaman)
                ->where('tgl_akhir_identifikasi', null)
                ->first();

            if ($existingRfid) {
                return $this->response->setJSON(['success' => false, 'error' => 'RFID ' . $rfid_tanaman . ' sudah terdaftar di tanaman yang aktif, tolong diganti.']);
            }
        }

        // Fetch the status_name based on status_id
        $statusModel = new StatusModel();
        $status = $statusModel->find($status_id); // Get status by ID
        $status_name = $status ? $status['nama_status'] : null;

        // Fetch the aktivitas_name based on aktivitas_id
        $aktivitasModel = new TipeAktivitasModel();
        $aktivitas = $aktivitasModel->find($aktivitas_id); // Get aktivitas by ID
        $aktivitas_name = $aktivitas ? $aktivitas['nama_aktivitas'] : null;

        // If either the status_name or aktivitas_name is not found, return an error
        if (!$status_name || !$aktivitas_name) {
            return $this->response->setJSON(['success' => false, 'error' => 'Tipe Aktivitas atau Status tidak valid.']);
        }

        // Check if 'aktivitas_name' is 'seleksi', 'status_name' is 'pc', and sister is 0
        // Convert both status_name and aktivitas_name to lowercase for comparison
        if (strtolower($aktivitas_name) === 'seleksi' && strtolower($status_name) === 'pc' && (int)$sister === 0) {
            $minggu_tanam = $week; // If 'seleksi' and 'pc' and sister is 0, use the week value
        } else {
            $minggu_tanam = 0;
        }

        // Siapkan data untuk dimasukkan ke dalam tabel 'tanaman'
        $data = [
            'tgl_mulai_identifikasi' => date('Y-m-d H:i:s'),
            'hs_id' => $hs_id,
            'rfid_tanaman' => $rfid_tanaman,
            'latitude_tanam' => (float)$latitude,
            'longitude_tanam' => (float)$longitude,
            'no_titik_tanam' => (int)$no_titik_tanam,
            'status_id' => (int)$status_id,  // Use the correct status_id
            'sister' => (int)$sister,
            'losses_id' => null,  // Nilai default
            'deskripsi_loses' => null,  // Nilai default
            'tgl_akhir_identifikasi' => null,  // Nilai default
            'minggu' => (int)$minggu_tanam,  // Set minggu value based on the condition
            'nama_karyawan' => $nama_karyawan,
            'npk' => $npk,
            'aktivitas_id' => (int)$aktivitas_id, // Use the correct aktivitas_id
        ];

        // Insert data ke dalam tabel 'tanaman' menggunakan TanamanModel
        $tanamanModel = new TanamanModel();
        if ($tanamanModel->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil disimpan.']);
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Gagal menyimpan data.']);
        }
    }

    public function insertTanamanDataShooting()
    {
        $data = $this->request->getPost();

        // Get the necessary data from the request
        $ptEstateId = $data['pt_estate'] ?? null;
        $blokId = $data['blok_id'] ?? null;
        $tanamanIds = $data['tanaman_id'] ?? [];
        $rfidTanamanArray = $data['rfid_tanaman'] ?? [];
        $newRfidArray = $data['new_rfid'] ?? [];
        $updateRfidCheckboxes = $data['update_rfid'] ?? [];
        $nama_karyawan = $this->request->getPost('nama');
        $npk = $this->request->getPost('npk');

        // Validate necessary fields
        if (!$ptEstateId || !$blokId || empty($tanamanIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Required fields are missing.']);
        }

        // Get the hsId based on ptEstateId and blokId
        $hsId = $this->getHsIdByPtEstateAndBlok($ptEstateId, $blokId);

        if ($hsId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pernyataan Hektar tidak ditemukan.']);
        }

        $tanamanModel = new TanamanModel();
        $aktivitasModel = new TipeAktivitasModel();
        $currentTime = date('Y-m-d H:i:s');

        // Fetch 'shooting' aktivitas_id
        $shootingAktivitas = $aktivitasModel->where('LOWER(nama_aktivitas)', 'shooting')->first();
        if (!$shootingAktivitas) {
            return $this->response->setJSON(['success' => false, 'message' => 'Aktivitas Shooting tidak ditemukan.']);
        }
        $shootingAktivitasId = $shootingAktivitas['aktivitas_id'];

        // Loop through each selected tanaman and process
        foreach ($tanamanIds as $index => $tanamanIdToUpdate) {
            // Only process if the 'update_rfid' checkbox is checked
            if (isset($updateRfidCheckboxes[$index]) && $updateRfidCheckboxes[$index] === 'on') {
                $currentRfid = $rfidTanamanArray[$index] ?? null;

                if (empty($tanamanIdToUpdate)) {
                    continue; // Skip if Tanaman ID is empty
                }

                // Get the existing tanaman data
                $tanamanData = $tanamanModel->find($tanamanIdToUpdate);

                // Check if RFID update is needed
                if ($currentRfid) {
                    // If RFID has been updated, set the current date as the end date of the previous identification
                    $tanamanData['rfid_tanaman'] = $currentRfid;
                    $tanamanData['tgl_akhir_identifikasi'] = $currentTime; // Set the end identification date for the old record
                }

                // Update the original record
                $tanamanModel->update($tanamanIdToUpdate, $tanamanData);

                // Prepare data for the new 'shooting' activity record
                $newTanamanData = [
                    'rfid_tanaman' => $newRfidArray[$index] ?? $currentRfid, // Use new RFID if provided
                    'pt_estate_id' => $ptEstateId,
                    'blok_id' => $blokId,
                    'hs_id' => $hsId,
                    'aktivitas_id' => $shootingAktivitasId,  // Set the aktivitas_id to 'shooting'
                    'no_titik_tanam' => $tanamanData['no_titik_tanam'],
                    'longitude_tanam' => $tanamanData['longitude_tanam'],
                    'latitude_tanam' => $tanamanData['latitude_tanam'],
                    'status_id' => $tanamanData['status_id'], // Keep the original status_id
                    'sister' => $tanamanData['sister'],  // Retain sister value from the original record
                    'tgl_mulai_identifikasi' => $currentTime, // Set the start identification date
                    'tgl_akhir_identifikasi' => null, // New record should have no end date yet
                    'nama_karyawan' => $nama_karyawan, // Add employee name to the new record
                    'npk' => $npk, // Add NPK to the new record
                ];

                // Insert the new record for shooting activity
                $tanamanModel->insert($newTanamanData);
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diperbarui dan ditambahkan.']);
    }

    public function getActiveTanamanDataSeleksi($noTitikTanam)
    {

        // Get ptEstateId and blokId from the request
        $ptEstateId = $this->request->getVar('pt_estate_id'); // Assuming pt_estate_id is passed in the request
        $blokId = $this->request->getVar('blok_id'); // Assuming blok_id is passed in the request

        // Fetch all tipe_aktivitas from the database
        $tipeAktivitasModel = new TipeAktivitasModel();
        $tipeAktivitasData = $tipeAktivitasModel->findAll();

        // Find the aktivitas_id where nama_aktivitas = 'seleksi'
        $aktivitasId = null;
        foreach ($tipeAktivitasData as $aktivitas) {
            if (strtolower($aktivitas['nama_aktivitas']) === 'seleksi') {
                $aktivitasId = $aktivitas['aktivitas_id'];
                break;
            }
        }

        if ($aktivitasId === null) {
            // If no 'seleksi' activity is found, return an error
            log_message('error', 'No tipe aktivitas found with nama_aktivitas = "seleksi"');
            return $this->response->setJSON(['success' => false, 'error' => 'No tipe aktivitas "seleksi" found.']);
        }

        // Get hsId based on ptEstateId and blokId
        $hsId = $this->getHsIdByPtEstateAndBlok($ptEstateId, $blokId);

        // Load the TanamanModel
        $tanamanModel = new TanamanModel();

        // Call the model method with noTitikTanam, hsId, and aktivitasId
        $activeTanaman = $tanamanModel->getNoActiveTanamDataSeleksi($noTitikTanam, $hsId, $aktivitasId);

        // If active tanaman data exists, process it
        if ($activeTanaman) {
            log_message('info', 'Active Tanaman Data Found');

            // Process the data to handle null RFID
            foreach ($activeTanaman as &$tanaman) {
                // If RFID is null, replace it with an empty string
                if (is_null($tanaman['rfid_tanaman'])) {
                    $tanaman['rfid_tanaman'] = '';  // Set empty string instead of 'belum ada RFID'
                }
            }

            // Return the response with the modified data
            return $this->response->setJSON(['success' => true, 'tanaman' => $activeTanaman]);
        } else {
            log_message('info', 'No Active Tanaman Data Found');
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
