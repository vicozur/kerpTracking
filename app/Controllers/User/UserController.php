<?php

namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\AssignModel;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel, $assignModel;
    protected $session;

    public function __construct()
    {
        $this->assignModel = new AssignModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => "Regitrarme en TrakingKerp",
            'titleMod' => "Registro de Usuario",
        ];

        return view('user/userform', $data);
    }

    public function save()
    {
        $user_idF = trim($this->request->getPost('user_id'));
        $ci = trim($this->request->getPost('ci'));
        $name = trim($this->request->getPost('name'));
        $lastName = trim($this->request->getPost('lastname'));
        $email = trim($this->request->getPost('email'));

        // Generar username básico
        $apPatern = explode(" ", $lastName);
        $username = strtolower(substr($name, 0, 1) . ($apPatern[0] ?? ''));

        $data = [
            'username'  => $username,
            'name'      => $name,
            'lastname'  => strtoupper($lastName),
            'email'     => $email,
            'phone'     => trim($this->request->getPost('phone')),
            'ci'        => $ci,
            'ext'       => trim($this->request->getPost('ext')),
            'user_type' => trim($this->request->getPost('tipo')),
            'updated_user' => $this->session->get('user') ?? 'system'
        ];

        // 1. BUSCAR SI EL USUARIO YA EXISTE POR CI
        //$existingUser = $this->userModel->getUserByci($ci);
        $existingUser = $this->userModel->where('ci', $ci)->asObject()->first();

        if (!$existingUser) {
            // --- LÓGICA DE REGISTRO NUEVO ---
            $data['created_user'] = $this->session->get('user') ?? 'system';
            $data['password'] = password_hash($username, PASSWORD_DEFAULT);

            $insertId = $this->userModel->insert($data);

            if ($insertId) {
                $this->assignModel->insert([
                    'user_user_id'       => $insertId,
                    'profile_profile_id' => 2, // Perfil por defecto
                    'created_user'       => $data['created_user']
                ]);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Usuario registrado nuevo por CI.']);
            }
        } else {
            // --- LÓGICA DE ACTUALIZACIÓN ---
            $userId = $existingUser->user_id; // Asumiendo que tu objeto tiene user_id
            if ($user_idF == $userId) {
                $profileId = $this->request->getPost('profileId') ?? 2;
            
                $this->userModel->update($userId, $data);

                // Actualizar perfil (ajusta el where según tu tabla de asignación)
                $this->assignModel->where('user_user_id', $userId)
                    ->set(['profile_profile_id' => $profileId])
                    ->update();

                return $this->response->setJSON(['status' => 'success', 'message' => 'Datos de usuario actualizados mediante CI.']);
            }     
        }
    }
    
    
}
