<?php

namespace App\Models;

use CodeIgniter\Model;

class TipeAktivitasModel extends Model
{
    protected $table = 'tipe_aktivitas'; // The table name
    protected $primaryKey = 'aktivitas_id'; // The primary key of the table
    protected $allowedFields = ['nama_aktivitas']; // Fields that are allowed for insert/update

    protected $returnType = 'array'; // The return type for this model

    // Get all Tipe Aktivitas
    public function getAll()
    {
        return $this->findAll();
    }

    // Get a single Tipe Aktivitas by its ID
    public function getById($id)
    {
        return $this->find($id);
    }

    // Insert a new Tipe Aktivitas
    public function insertTipeAktivitas($data)
    {
        return $this->insert($data);
    }

    // Update an existing Tipe Aktivitas by its ID
    public function updateTipeAktivitas($id, $data)
    {
        return $this->update($id, $data);
    }

    // Delete a Tipe Aktivitas by its ID
    public function deleteTipeAktivitas($id)
    {
        return $this->delete($id);
    }
}
