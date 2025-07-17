<?php

namespace App\Controllers;

use App\Models\MasterLokasiModel;
use App\Models\KaryawanModel;
use App\Models\GroupModel;
use App\Models\RfidGroupModel;
use App\Models\UsersModel;
use App\Models\PolicyModel;
use App\Models\MasterLossesModel;
use App\Models\PtEstateModel;
use App\Models\MasterBlokModel;
use App\Models\StatusModel;
use App\Models\HectareStatementModel;
use App\Models\TimbanganModel;
use App\Models\TanamanModel;
use App\Models\TipeAktivitasModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class AndroidController extends ResourceController
{
    use ResponseTrait;
    protected $modelName = 'App\Models\UsersModel';
    protected $format = 'json';

    public function loginProcessAndroid()
    {
        $model   = new UsersModel();
        $json    = $this->request->getJSON();

        if (! $json) {
            return $this->respond([
                'success' => false,
                'message' => 'Invalid JSON data provided.'
            ], 400);
        }

        $npk      = $json->npk      ?? null;
        $password = $json->password ?? null;

        if (empty($npk) || empty($password)) {
            return $this->respond([
                'success' => false,
                'message' => 'NPK dan Password harus diisi.'
            ], 400);
        }

        if (! $user = $model->where('npk', $npk)->first()) {
            return $this->respond([
                'success' => false,
                'message' => 'NPK atau Password Salah'
            ], 401);
        }

        if (! password_verify($password, $user['password'])) {
            return $this->respond([
                'success' => false,
                'message' => 'NPK atau Password Salah'
            ], 401);
        }

        // JWT creation
        $issuedAt   = time();
        $ttl        = (int) env('JWT_TTL', 3600);
        $expire     = $issuedAt + $ttl;
        $secretKey  = env('JWT_SECRET');

        $payload = [
            'iat'  => $issuedAt,           // Issued at: time when the token was generated
            'exp'  => $expire,             // Expiration time
            'sub'  => $user['npk'],         // Subject (user ID)
            'npk'  => $user['npk'],        // custom claim
            'role' => $user['role'],       // custom claim
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        return $this->respond([
            'success'    => true,
            'message'    => 'Login Berhasil',
            'access_token' => $jwt,
            'token_type'   => 'Bearer',
            'expires_in'   => $ttl
        ], 200);
    }
    public function download()
    {
        $json = $this->request->getJSON();

        if (! $json || ! isset($json->npk)) {
            return $this->respond([
                'success' => false,
                'message' => 'Invalid JSON request. NPK is required.'
            ], 400);
        }

        $npk = $json->npk;

        // 1) Check NPK exists
        $karyawanModel = new KaryawanModel();
        $karyawan     = $karyawanModel
            ->where('npk', $npk)
            ->first();

        if (! $karyawan) {
            return $this->respond([
                'success' => false,
                'message' => "NPK “{$npk}” not found."
            ], 404);
        }

        // 2) Load all the “master” data
        $lokasiModel           = new MasterLokasiModel();
        $groupModel            = new GroupModel();
        $rfidGroupModel        = new RfidGroupModel();
        $usersModel            = new UsersModel();
        $policyModel           = new PolicyModel();
        $masterLossesModel     = new MasterLossesModel();
        $ptEstateModel         = new PtEstateModel();
        $masterBlokModel       = new MasterBlokModel();
        $statusModel           = new StatusModel();
        $hectareStatementModel = new HectareStatementModel();
        $timbanganModel        = new TimbanganModel();
        $tanamanModel          = new TanamanModel();
        $tipeAktivitasModel    = new TipeAktivitasModel();

        $data = [
            'lokasi'             => $lokasiModel->findAll(),
            'karyawan'           => $karyawan,
            'karyawan_list'      => $karyawanModel->findAll(),
            'group'              => $groupModel->findAll(),
            'rfid_group'         => $rfidGroupModel->findAll(),
            'users'              => $usersModel->findAll(),
            'policy'             => $policyModel->findAll(),
            'master_losses'      => $masterLossesModel->findAll(),
            'pt_estate'          => $ptEstateModel->findAll(),
            'master_blok'        => $masterBlokModel->findAll(),
            'status'             => $statusModel->findAll(),
            'hectare_statement'  => $hectareStatementModel->findAll(),
            'timbangan'          => $timbanganModel->findAll(),
            'tanaman'            => $tanamanModel->findAll(),
            'tipe_aktivitas'     => $tipeAktivitasModel->findAll(),
        ];

        return $this->respond([
            'success' => true,
            'message' => 'Data berhasil diunduh.',
            'data'    => $data
        ], 200);
    }

    public function uploadData()
    {
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->respond([
                'success' => false,
                'message' => 'Invalid JSON data provided.'
            ], 400);
        }

        $uploadedTanaman = $json->tanaman ?? [];
        $uploadedTimbangan = $json->timbangan ?? [];

        $tanamanModel = new TanamanModel();
        $timbanganModel = new TimbanganModel();

        $this->db = \Config\Database::connect(); // Get database instance for transactions

        $this->db->transBegin(); // Start transaction

        try {
            // Process Tanaman data first
            foreach ($uploadedTanaman as $tanamanData) {
                // Convert object to array for model operations
                $tanamanArray = (array) $tanamanData;

                // Remove client-side ID as DB auto-increments it.
                unset($tanamanArray['tanaman_id']);

                // Handle potential null/empty strings for nullable fields
                foreach (['tgl_akhir_identifikasi', 'losses_id', 'deskripsi_loses', 'QRCode'] as $field) {
                    if (isset($tanamanArray[$field]) && (empty($tanamanArray[$field]) || strtolower($tanamanArray[$field]) === 'null')) {
                        $tanamanArray[$field] = null;
                    }
                }
                // Ensure numeric fields are correctly cast if they might come as strings
                foreach (['sister', 'minggu'] as $field) {
                    if (isset($tanamanArray[$field])) {
                        $tanamanArray[$field] = (int) $tanamanArray[$field];
                    }
                }


                // --- Refined Logic for Tanaman Updates/Inserts ---

                // 1. Check for Exact Duplicate (Skip)
                // This checks if the uploaded record is IDENTICAL to an existing one in the DB.
                // This is crucial for records that were downloaded and haven't changed, or
                // records that were already uploaded and correctly processed.
                $exactMatchQuery = $tanamanModel;
                foreach ($tanamanArray as $key => $value) {
                    if ($key === 'tanaman_id') { // Skip client-side ID for exact match check
                        continue;
                    }
                    // CodeIgniter's where method handles null values correctly (translates to IS NULL)
                    $exactMatchQuery->where($key, $value);
                }
                $exactExistingTanaman = $exactMatchQuery->first();

                if ($exactExistingTanaman) {
                    log_message('debug', 'Skipping exact duplicate tanaman entry for RFID: ' . $tanamanArray['rfid_tanaman'] . ' and Tgl Mulai: ' . $tanamanArray['tgl_mulai_identifikasi'] . ' Aktivitas: ' . $tanamanArray['aktivitas_id']);
                    continue; // Skip this entry, as it's already perfectly synced
                }

                // 2. Find an existing record by rfid_tanaman, tgl_mulai_identifikasi, and aktivitas_id
                // This identifies if we are dealing with an update to an *already existing activity instance*.
                $existingActivityInstance = $tanamanModel
                    ->where('rfid_tanaman', $tanamanArray['rfid_tanaman'])
                    ->where('tgl_mulai_identifikasi', $tanamanArray['tgl_mulai_identifikasi'])
                    ->where('aktivitas_id', $tanamanArray['aktivitas_id'])
                    ->first();

                if ($existingActivityInstance) {
                    // This is an update to an existing activity instance (e.g., setting tgl_akhir_identifikasi or losses_id).
                    $tanamanModel->update($existingActivityInstance['tanaman_id'], $tanamanArray);
                    log_message('debug', 'Updated existing tanaman activity instance with RFID: ' . $tanamanArray['rfid_tanaman'] . ' Tgl Mulai: ' . $tanamanArray['tgl_mulai_identifikasi'] . ' Aktivitas: ' . $tanamanArray['aktivitas_id']);
                } else {
                    // This is a new activity instance.
                    // Before inserting the new activity, check if there's a currently active (unclosed) activity for this RFID.
                    $existingActiveTanamanForRfid = $tanamanModel
                        ->where('rfid_tanaman', $tanamanArray['rfid_tanaman'])
                        ->where('tgl_akhir_identifikasi IS NULL')
                        ->where('losses_id IS NULL')
                        ->orderBy('tgl_mulai_identifikasi', 'DESC') // Get the most recent active one
                        ->first();

                    if ($existingActiveTanamanForRfid) {
                        // If an active record exists, close it by setting its tgl_akhir_identifikasi.
                        // This signifies the end of the previous activity for this plant.
                        $tanamanModel->update($existingActiveTanamanForRfid['tanaman_id'], [
                            'tgl_akhir_identifikasi' => $tanamanArray['tgl_mulai_identifikasi'] ?? date('Y-m-d H:i:s')
                        ]);
                        log_message('debug', 'Closed previous active tanaman (ID: ' . $existingActiveTanamanForRfid['tanaman_id'] . ') for RFID: ' . $tanamanArray['rfid_tanaman'] . ' due to new activity.');
                    }

                    // Insert the new tanaman record for the new activity instance.
                    $tanamanModel->insert($tanamanArray);
                    log_message('debug', 'Inserted new tanaman activity instance for RFID: ' . $tanamanArray['rfid_tanaman'] . ' Tgl Mulai: ' . $tanamanArray['tgl_mulai_identifikasi'] . ' Aktivitas: ' . $tanamanArray['aktivitas_id']);
                }
            }

            // Process Timbangan data
            // Recommendation: Always insert new timbangan data unless an exact duplicate (based on key fields) is found.
            // This prevents re-inserting records that were already downloaded and exist on the server.
            foreach ($uploadedTimbangan as $timbanganData) {
                // Convert object to array for model operations
                $timbanganArray = (array) $timbanganData;

                // Remove client-side ID as DB auto-increments it.
                unset($timbanganArray['transaksi_id']);

                // Get the server's tanaman_id based on rfid_tanaman from the uploaded timbangan data.
                // This is crucial because the Android app uses a local tanaman_id, but the server
                // needs the actual tanaman_id from its own 'tanaman' table.
                // We need to find the *correct* tanaman_id for the timbangan entry.
                // This typically means the currently active or most recent relevant tanaman_id for that RFID.
                // Given the new tanaman logic, it's safer to find the latest one based on rfid_tanaman.
                $serverTanaman = $tanamanModel->where('rfid_tanaman', $timbanganArray['rfid_tanaman'])
                    ->orderBy('tgl_mulai_identifikasi', 'DESC') // Get the most recent
                    ->first();

                if (!$serverTanaman) {
                    // If the related 'tanaman' record is not found on the server,
                    // it indicates a data integrity issue or out-of-sync data.
                    throw new \Exception("Tanaman with RFID " . $timbanganArray['rfid_tanaman'] . " not found in database for timbangan entry. Ensure tanaman data is uploaded first.");
                }

                // Update the tanaman_id in the timbangan data to the server's ID
                $timbanganArray['tanaman_id'] = $serverTanaman['tanaman_id'];

                // Ensure berat_timbangan is a float/decimal
                if (isset($timbanganArray['berat_timbangan'])) {
                    $timbanganArray['berat_timbangan'] = (float) $timbanganArray['berat_timbangan'];
                }

                // --- Deduplication Logic for Timbangan ---
                // Check if an identical timbangan record already exists based on key fields.
                // This prevents re-inserting records that were already downloaded and exist on the server.
                // IMPORTANT: Removed 'tanaman_id' from the WHERE clause for deduplication
                // because it can change on the server side and cause re-insertion of old data.
                // Focus on external identifiers and transaction details.
                $existingTimbangan = $timbanganModel
                    ->where('rfid_tanaman', $timbanganArray['rfid_tanaman'])
                    ->where('tgl_transaksi', $timbanganArray['tgl_transaksi'])
                    ->where('berat_timbangan', $timbanganArray['berat_timbangan'])
                    ->where('group_id', $timbanganArray['group_id'])
                    // ->where('tanaman_id', $timbanganArray['tanaman_id']) // REMOVED THIS LINE
                    ->where('hs_id', $timbanganArray['hs_id'] ?? null)
                    ->where('status_id', $timbanganArray['status_id'] ?? null)
                    ->first();

                if ($existingTimbangan) {
                    // Record already exists, skip insertion.
                    log_message('debug', 'Skipping duplicate timbangan entry for RFID: ' . $timbanganArray['rfid_tanaman'] . ' and Tgl Transaksi: ' . $timbanganArray['tgl_transaksi']);
                } else {
                    // Insert timbangan data if no identical record is found.
                    $timbanganModel->insert($timbanganArray);
                    log_message('debug', 'Inserted new timbangan for RFID: ' . $timbanganArray['rfid_tanaman'] . ' with server tanaman_id: ' . $serverTanaman['tanaman_id']);
                }
            }

            $this->db->transCommit(); // Commit transaction

            return $this->respond([
                'success' => true,
                'message' => 'Data berhasil diunggah.'
            ], 200);
        } catch (\Exception $e) {
            $this->db->transRollback(); // Rollback transaction on error
            log_message('error', 'Upload failed: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Gagal mengunggah data: ' . $e->getMessage()
            ], 500);
        }
    }
}
