<?php

namespace App\Controllers;


use App\Models\UsersModel;
use App\Models\karyawanModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class AuthController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\UsersModel';
    protected $format = 'json';

    public function login()
    {
        return view('auth-login');
    }

    public function register()
    {
        return view('auth-register');
    }

    public function registerProcess()
    {
        $model         = new UsersModel();
        $karyawanModel = new karyawanModel();

        // Ambil JSON payload
        $json = $this->request->getJSON();
        if (! $json) {
            return $this->respond([
                'success' => false,
                'message' => 'Invalid JSON data provided.'
            ], 400);
        }

        $npk      = $json->npk      ?? null;
        $username = $json->username ?? null;
        $password = $json->password ?? null;

        // Validasi keharusan field
        if (empty($npk) || empty($username) || empty($password)) {
            return $this->respond([
                'success' => false,
                'message' => 'NPK, Username, dan Password harus diisi.'
            ], 400);
        }

        // Cek NPK terdaftar di karyawan
        if (! $karyawanModel->where('npk', $npk)->first()) {
            return $this->respond([
                'success' => false,
                'message' => "NPK tidak terdaftar : {$npk}"
            ], 400);
        }

        // Cek duplikat username atau npk
        if ($model->where('username', $username)->first() || $model->where('npk', $npk)->first()) {
            return $this->respond([
                'success' => false,
                'message' => 'Username atau NPK sudah digunakan'
            ], 409);
        }

        $data = [
            'username' => $username,
            'npk'      => $npk,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => 'user'
        ];

        try {
            $model->save($data);
            return $this->respondCreated([
                'success' => true,
                'message' => 'Pendaftaran berhasil'
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Pendaftaran gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function loginProcess()
    {
        $session = session();
        $model = new UsersModel();
        $json = $this->request->getJSON();

        if ($json === null) {
            return $this->respond([
                'success' => false,
                'message' => 'Invalid JSON data provided.'
            ], 400);
        }

        $npk = $json->npk ?? null;
        $password = $json->password ?? null;

        if (empty($npk) || empty($password)) {
            return $this->respond([
                'success' => false,
                'message' => 'NPK dan Password harus diisi.'
            ], 400);
        }

        $user = $model->where('npk', $npk)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Set user session
            $sessionData = [
                'npk' => $user['npk'],
                'isLoggedIn' => true
            ];
            $session->set($sessionData);
            return $this->respond([
                'success' => true,
                'message' => 'Login Berhasil'
            ], 200);
        } else {
            return $this->respond([
                'success' => false,
                'message' => 'NPK atau Password Salah'
            ], 401); // Use 401 Unauthorized
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth-login'));
    }
}
