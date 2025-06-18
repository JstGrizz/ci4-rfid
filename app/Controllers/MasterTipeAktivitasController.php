<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TipeAktivitasModel;

class MasterTipeAktivitasController extends ResourceController
{
    public function index()
    {
        $model = new TipeAktivitasModel();
        $data['tipe_aktivitas'] = $model->findAll();
        return view('admin/master-tipe-aktivitas', $data);
    }

    public function showAll()
    {
        $model = new TipeAktivitasModel();
        $data = $model->findAll();
        return $this->response->setJSON(['tipe_aktivitas' => $data]);
    }

    public function show($id = null)
    {
        $model = new TipeAktivitasModel();
        $data = $model->find($id);
        if (!$data) {
            return $this->failNotFound('Tipe Aktivitas dengan ID ' . $id . ' tidak ditemukan');
        }

        return $this->respond($data);
    }

    public function add()
    {
        return view('admin/create-tipe-aktivitas', [
            'title' => 'Tambah Tipe Aktivitas'
        ]);
    }

    public function create()
    {
        $json = $this->request->getJSON();
        $model = new TipeAktivitasModel();

        // Ambil dan bersihkan input
        $namaAktivitas = trim($json->nama_aktivitas ?? '');

        // Validasi: nama_aktivitas tidak boleh kosong
        if (empty($namaAktivitas)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama Aktivitas harus diisi.'
            ]);
        }

        // Cek duplikat: apakah sudah ada tipe_aktivitas dengan nama_aktivitas yang sama?
        $duplicate = $model
            ->where('nama_aktivitas', $namaAktivitas)
            ->first();

        if ($duplicate) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipe Aktivitas dengan nama tersebut sudah ada.'
            ]);
        }

        // Siapkan data untuk insert
        $data = [
            'nama_aktivitas' => $namaAktivitas
        ];

        try {
            $insertId = $model->insert($data);

            // Kalau insert gagal, $insertId akan false atau Model->errors() tidak kosong
            if (!$insertId || $model->errors()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan Tipe Aktivitas.',
                    'errors'  => $model->errors()
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tipe Aktivitas berhasil ditambahkan.',
                'id'      => $insertId
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menambahkan Tipe Aktivitas: ' . $e->getMessage()
            ]);
        }
    }

    public function edit($id = null)
    {
        $model = new TipeAktivitasModel();
        $data = $model->find($id);

        if (!$data) {
            return redirect()->to('/master-tipe-aktivitas')->with('error', 'Tipe Aktivitas tidak ditemukan.');
        }

        return view('admin/edit-tipe-aktivitas', [
            'tipe_aktivitas' => $data
        ]);
    }

    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid Tipe Aktivitas ID.'
            ]);
        }

        $json = $this->request->getJSON();
        $model = new TipeAktivitasModel();
        $namaAktivitas = trim($json->nama_aktivitas ?? '');

        // Validasi: nama_aktivitas tidak boleh kosong
        if (empty($namaAktivitas)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama Aktivitas harus diisi.'
            ]);
        }

        // Cek apakah tipe_aktivitas dengan ID tersebut benar-benar ada
        $existing = $model->find($id);
        if (!$existing) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipe Aktivitas dengan ID tersebut tidak ditemukan.'
            ]);
        }

        // Siapkan data untuk update
        $data = [
            'nama_aktivitas' => $namaAktivitas
        ];

        try {
            $result = $model->update($id, $data);
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipe Aktivitas berhasil diperbarui.'
                ]);
            } else {
                // Bila model->update mengembalikan false, bisa jadi tidak ada perubahan
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tidak ada perubahan pada data.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memperbarui Tipe Aktivitas: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id = null)
    {
        $model = new TipeAktivitasModel();
        if ($model->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tipe Aktivitas berhasil dihapus.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus Tipe Aktivitas.'
            ]);
        }
    }
}
