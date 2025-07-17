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
        // 1. Get hsId
        $hsId = $this->getHsIdByPtEstateAndBlok($ptEstateId, $blokId);

        if ($hsId === null) {
            // log_message('debug', 'Pernyataan Hektar tidak ditemukan for ptEstateId: ' . $ptEstateId . ' blokId: ' . $blokId);
            return $this->response->setJSON(['success' => false, 'error' => 'Pernyataan Hektar tidak ditemukan.']);
        }

        $tanamanModel = new TanamanModel();
        $statusModel = new StatusModel(); // Instantiate StatusModel once

        // 2. Fetch the latest status ID for the given plant coordinates and ID
        // This method should now be ordering by 'tanaman_id' DESC as per our last discussion.
        $latestStatusID = $tanamanModel->fetchLatestStatusForTitikTanam(
            $longitudeTanam,
            $latitudeTanam,
            $noTitikTanam,
            $hsId
        );

        // log_message('debug', 'Latest Status ID fetched: ' . ($latestStatusID ?? 'NULL') .
        //     ' for noTitikTanam: ' . $noTitikTanam .
        //     ', longitude: ' . $longitudeTanam .
        //     ', latitude: ' . $latitudeTanam .
        //     ', hsId: ' . $hsId);

        if ($latestStatusID !== null) {
            // 3. Check if the latest status is currently 'active' (tgl_akhir_identifikasi is NULL)
            $isActive = $tanamanModel->checkIfStatusIsActive(
                $longitudeTanam,
                $latitudeTanam,
                $noTitikTanam,
                $hsId,
                $latestStatusID
            );
            // log_message('debug', 'Is Status Active for latestStatusID ' . $latestStatusID . ': ' . ($isActive ? 'true' : 'false'));

            $statusOptions = [];
            if ($isActive) {
                // SCENARIO A: The latest status is currently ACTIVE (tgl_akhir_identifikasi IS NULL).
                // In this case, NO transition is recommended. Only the current active status is shown.
                // log_message('debug', 'Entering isActive block. Status is active, showing current status only.');
                $currentStatusInfo = $statusModel->find($latestStatusID);
                $statusOptions[] = ['value' => $currentStatusInfo['status_id'], 'label' => $currentStatusInfo['nama_status']];
            } else {
                // SCENARIO B: The latest status is NOT active (tgl_akhir_identifikasi is NOT NULL).
                // This indicates a closed status, so we recommend the NEXT status in the sequence.
                // log_message('debug', 'Entering NOT active block. Status is inactive, suggesting next status in sequence.');
                $nextStatus = $statusModel->determineNextStatus($latestStatusID);
                $statusOptions[] = $nextStatus;
            }

            // log_message('debug', 'Final Status Options determined: ' . json_encode($statusOptions));
            return $this->response->setJSON(['success' => true, 'statusOptions' => $statusOptions]);
        } else {
            // 4. No latest status found for the given parameters (first time identifying this plant).
            // Suggest the default 'PC' status.
            // log_message('debug', 'No latest status found. Suggesting default PC status.');
            $defaultStatus = $statusModel->where('nama_status', 'PC')
                ->orWhere('nama_status', 'pc')
                ->first();

            if ($defaultStatus) {
                // log_message('debug', 'Default PC status found: ' . json_encode($defaultStatus));
                return $this->response->setJSON([
                    'success' => true,
                    'statusOptions' => [
                        ['value' => $defaultStatus['status_id'], 'label' => $defaultStatus['nama_status']]
                    ]
                ]);
            }
        }

        // log_message('error', 'Status not found fallback for unknown reason.');
        return $this->response->setJSON(['success' => false, 'error' => 'Status tidak ditemukan.']);
    }

    public function fetchSister()
    {
        // 1) Grab query params
        $noTitikTanam    = $this->request->getGet('noTitikTanam');
        $ptEstateId      = $this->request->getGet('ptEstateId');
        $blokId          = $this->request->getGet('blokId');
        $currentStatusId = $this->request->getGet('currentStatusId');

        if (! $noTitikTanam || ! $ptEstateId || ! $blokId || ! $currentStatusId) {
            return $this->response->setJSON([
                'success' => false,
                'error'   => 'Parameter tidak lengkap.'
            ]);
        }

        // 2) Lookup hs_id
        $hsModel = new HectareStatementModel();
        $hs      = $hsModel->getHectareStatementByPtEstateIdAndBlockId($ptEstateId, $blokId);
        if (! $hs) {
            return $this->response->setJSON([
                'success' => false,
                'error'   => 'Pernyataan Hektar tidak ditemukan.'
            ]);
        }
        $hsId = $hs['hs_id'];

        // 3) figure out what the *next* status would be
        $statusModel   = new StatusModel();
        $statusToUse = $currentStatusId;

        // 4) what's the very first status in the cycle?
        $firstRow      = $statusModel
            ->orderBy('status_id', 'ASC')
            ->findAll(1);
        $firstStatusId = $firstRow[0]['status_id'];

        // 5) now fetch counts *for that next status*
        $tanamanModel = new TanamanModel();
        // 5) fetch counts *for that same* status
        $s = $tanamanModel->fetchLatestSisterForTitikTanamAndStatus(
            $noTitikTanam,
            $hsId,
            $statusToUse
        );

        if ($s['active_count'] > 0) {
            // there are still live records at this status, so next sister = max + 1
            $recommendedSister = $s['max_sister'] + 1;
        } else {
            // no active rows means this is the first sister at this status
            $recommendedSister = 0;
        }

        return $this->response->setJSON([
            'success' => true,
            'sister'  => $recommendedSister,
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
            return $this->response->setJSON(['success' => false, 'message' => 'Beberapa data wajib belum terisi.']);
        }

        // Log incoming data
        log_message('info', 'InsertTanamanDataShooting - Incoming data: ' . json_encode($data));

        // Get the hsId based on ptEstateId and blokId
        $hsId = $this->getHsIdByPtEstateAndBlok($ptEstateId, $blokId);
        log_message('info', 'hsId retrieved: ' . $hsId);

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
        log_message('info', 'Aktivitas Shooting found, aktivitas_id: ' . $shootingAktivitasId);

        foreach ($tanamanIds as $index => $tanamanIdToUpdate) {
            if (! isset($updateRfidCheckboxes[$index]) || $updateRfidCheckboxes[$index] !== 'on') {
                log_message('info', 'RFID update checkbox not checked for Tanaman ID: ' . $tanamanIdToUpdate);
                continue;
            }

            // This is the NEW RFID the user entered
            $newRfidValue = trim($newRfidArray[$index] ?? '');

            if (empty($tanamanIdToUpdate) || empty($newRfidValue)) {
                log_message('info', 'No new RFID provided for Tanaman ID: ' . $tanamanIdToUpdate);
                continue;
            }

            // Check if the new RFID already exists in the database with tgl_akhir_identifikasi = null
            $existingRfid = $tanamanModel->where('rfid_tanaman', $newRfidValue)
                ->where('tgl_akhir_identifikasi', null)
                ->first();

            if ($existingRfid) {
                log_message('error', 'RFID already exists with null tgl_akhir_identifikasi: ' . $newRfidValue);
                return $this->response->setJSON(['success' => false, 'message' => 'RFID ' . $newRfidValue . ' sudah ada dan masih aktif. Mohon gunakan RFID yang berbeda.']);
            }

            // Fetch the existing record
            $tanamanData = $tanamanModel->find($tanamanIdToUpdate);
            log_message('info', 'Existing tanaman data: ' . json_encode($tanamanData));

            // **Update** the original record's RFID & end date
            $tanamanData['rfid_tanaman'] = $newRfidValue;
            $tanamanData['tgl_akhir_identifikasi'] = $currentTime;
            $tanamanModel->update($tanamanIdToUpdate, $tanamanData);
            log_message('info', 'Updated original RFID to ' . $newRfidValue . ' for Tanaman ID: ' . $tanamanIdToUpdate);

            // Now insert the new “shooting” activity record
            $newTanamanData = [
                'rfid_tanaman'           => $newRfidValue,
                'pt_estate_id'           => $ptEstateId,
                'blok_id'                => $blokId,
                'hs_id'                  => $hsId,
                'aktivitas_id'           => $shootingAktivitasId,
                'no_titik_tanam'         => $tanamanData['no_titik_tanam'],
                'longitude_tanam'        => $tanamanData['longitude_tanam'],
                'latitude_tanam'         => $tanamanData['latitude_tanam'],
                'status_id'              => $tanamanData['status_id'],
                'sister'                 => $tanamanData['sister'],
                'tgl_mulai_identifikasi' => $currentTime,
                'tgl_akhir_identifikasi' => null,
                'nama_karyawan'          => $nama_karyawan,
                'npk'                    => $npk,
            ];
            log_message('info', 'New shooting record: ' . json_encode($newTanamanData));

            $tanamanModel->insert($newTanamanData);
            log_message('info', 'Inserted new shooting record for Tanaman ID: ' . $tanamanIdToUpdate);
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
            return $this->response->setJSON(['success' => false, 'error' => 'Tidak ada data tanaman aktif ditemukan.']);
        }
    }

    public function getActiveTanamanDataUpdate($noTitikTanam)
    {

        // Get ptEstateId and blokId from the request
        $ptEstateId = $this->request->getVar('pt_estate_id');
        $blokId = $this->request->getVar('blok_id');

        // Get hsId based on ptEstateId and blokId
        $hsId = $this->getHsIdByPtEstateAndBlok($ptEstateId, $blokId);

        // Load the TanamanModel
        $tanamanModel = new TanamanModel();

        // Call the model method with noTitikTanam, hsId, and aktivitasId
        $activeTanaman = $tanamanModel->getNoActiveTanamDataUpdate($noTitikTanam, $hsId);

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

        // Get the NPK and nama_karyawan from the post data
        $npk = $this->request->getPost('npk');
        $nama_karyawan = $this->request->getPost('nama');

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
                continue; // Skip if Tanaman ID is empty
            }

            $tanamanData = [];

            // --- Logika untuk update RFID ---
            $rfidToUse = $currentRfid;
            $isUpdateRfidChecked = isset($updateRfidCheckboxes[$index]) && $updateRfidCheckboxes[$index] === 'on';

            if ($isUpdateRfidChecked) {
                $newRfidValue = $newRfidArray[$index] ?? '';
                if (!empty($newRfidValue)) {
                    // Ensure it's a string
                    $newRfidValue = (string)$newRfidValue;  // Convert the RFID to string

                    // Check if the RFID is already used
                    $existingNewRfid = $tanamanModel
                        ->where('rfid_tanaman', $newRfidValue)
                        ->where('tgl_akhir_identifikasi', null)
                        ->first();

                    if ($existingNewRfid && $existingNewRfid['tanaman_id'] != $tanamanIdToUpdate) {
                        return $this->response->setJSON(['success' => false, 'message' => 'RFID ' . $newRfidValue . ' sudah terdaftar di tanaman yang aktif, tolong diganti.']);
                    }

                    $rfidToUse = $newRfidValue;
                }

                // Update npk and nama_karyawan if RFID is updated
                $tanamanData['npk'] = $npk;
                $tanamanData['nama_karyawan'] = $nama_karyawan;
            }

            $tanamanData['rfid_tanaman'] = $rfidToUse;

            // --- Logika untuk update Losses ---
            $isUpdateLossesChecked = isset($updateLossesCheckboxes[$index]) && $updateLossesCheckboxes[$index] === 'on';
            $lsId = $lossesIdArray[$index] ?? null;
            $deskripsi = $deskripsiLosesArray[$index] ?? ''; // Ensure it's an empty string if no input

            // Check if the losses checkbox is checked and if losses_id is provided
            if ($isUpdateLossesChecked && $lsId !== null) {
                $tanamanData['losses_id'] = $lsId;
                $tanamanData['deskripsi_loses'] = $deskripsi;
                $tanamanData['tgl_akhir_identifikasi'] = $currentTime;  // Set the end date for the identification

                // Update npk and nama_karyawan if losses is updated
                $tanamanData['npk'] = $npk;
                $tanamanData['nama_karyawan'] = $nama_karyawan;
            } else {
                // Reset loses if checkbox is not checked or lsId is null
                $currentTanamanStatus = $tanamanModel->find($tanamanIdToUpdate);
                if ($currentTanamanStatus) {
                    $tanamanData['losses_id'] = null;
                    $tanamanData['deskripsi_loses'] = null;
                    $tanamanData['tgl_akhir_identifikasi'] = null;
                }
            }

            // Update the tanaman record
            $updateResult = $tanamanModel->update($tanamanIdToUpdate, $tanamanData);

            if (!$updateResult) {
                log_message('error', 'Failed to update Tanaman ID ' . $tanamanIdToUpdate . '. Errors: ' . json_encode($tanamanModel->errors()));
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }
}
