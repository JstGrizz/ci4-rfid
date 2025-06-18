<?php

namespace App\Models;

use CodeIgniter\Model;

class TipeAktivitasModel extends Model
{
    protected $table         = 'tipe_aktivitas';  // Name of the table
    protected $primaryKey    = 'aktivitas_id';    // Primary key
    protected $allowedFields = ['aktivitas_id', 'aktivitas_name'];  // Fields to be used
    protected $returnType    = 'array';  // Data type to be returned (array)

    /**
     * Get all activities
     * 
     * @return array
     */
    public function getAllAktivitas()
    {
        return $this->findAll();  // Get all records
    }

    /**
     * Get an activity by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getAktivitasById($id)
    {
        return $this->where('aktivitas_id', $id)->first();  // Fetch a record by ID
    }

    /**
     * Add a new activity
     * 
     * @param array $data
     * @return bool
     */
    public function addAktivitas($data)
    {
        return $this->insert($data);  // Insert a new record
    }

    /**
     * Update an activity by ID
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateAktivitas($id, $data)
    {
        return $this->update($id, $data);  // Update the record
    }

    /**
     * Delete an activity by ID
     * 
     * @param int $id
     * @return bool
     */
    public function deleteAktivitas($id)
    {
        return $this->delete($id);  // Delete a record by ID
    }
}
