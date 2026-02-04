<?php

namespace App\Controllers;

// ESTA LÍNEA ES VITAL
use Google\Client as GoogleClient;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index(): string
    {
        return view('login');
    }

    public function authentication()
    {
        $session = session();
        $userModel = new UserModel();
        // Validar usuario
        $user = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userFind = $userModel->getUserByUserName($user);
        if (!$userFind) {
            return redirect()->back()->with('error', 'Usuario no encontrado o inactivo.');
        }
        // Verificar contraseña
        if (!password_verify($password, trim($userFind['password']))) {
            return redirect()->back()->with('error', 'Contraseña incorrecta.');
        }

        // Guardar datos en sesión
        $session->set([
            'userId' => $userFind['user_id'],
            'name' => $userFind['name'],
            'lastName' => $userFind['lastname'],
            'email' => $userFind['email'],
            'user' => $userFind['username'],
            'phone' => $userFind['phone'],
            'menuItems' => $this->menuItems,
            'loggedIn' => true
        ]);

        return redirect()->to('/home'); // Puedes redirigir al dashboard
    }

    public function passwordChange()
    {
        $session = session();
        $userModel = new UserModel();
        // Validar usuario
        $user = $this->request->getPost('user');
        $password = $this->request->getPost('password');

        $userFind = $userModel->getUserByUserName($user);
        if (!$userFind) {
            return redirect()->back()->with('error', 'Usuario no encontrado o inactivo.');
        }
        // Verificar contraseña
        if (!password_verify($password, trim($userFind['password']))) {
            return redirect()->back()->with('error', 'Contraseña incorrecta.');
        }

        // Guardar datos en sesión
        $session->set([
            'userId' => $userFind['user_id'],
            'name' => $userFind['name'],
            'lastName' => $userFind['lastname'],
            'email' => $userFind['email'],
            'user' => $userFind['username'],
            'phone' => $userFind['phone'],
            'menuItems' => $this->menuItems,
            'loggedIn' => true
        ]);

        if ($userFind[""]) {
            # code...
        }

        return redirect()->to('/home'); // Puedes redirigir al dashboard
    }

    public function updatePassword()
    {
        $session = session();
        $userModel = new UserModel();
        $current = $this->request->getPost('current_password');
        $new = $this->request->getPost('new_password');

        $userFind = $userModel->getUserByUserName($this->session->get('user'));
        if (!$userFind) {
            return $this->response->setJSON(['status' => 'error']);
        }

        if (!password_verify($current, trim($userFind['password']))) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El password ingresado no es el correcto']);
        }

        $data['password'] = password_hash($new, PASSWORD_DEFAULT);
        $userModel->update($userFind['user_id'], $data);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // Función para configurar el cliente de Google
    private function getClient()
    {
        $client = new GoogleClient();
        $client->setClientId(env('google.client_id'));
        $client->setClientSecret(env('google.client_secret'));
        
        //$client->setClientId('448405850018-rcufp6idnmkmdb4kv1ndqjltlhngqupm.apps.googleusercontent.com');
        //$client->setClientSecret('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'); // Espacio eliminado
        
        $client->setRedirectUri('http://localhost/kerpTracking/public/login/googleAuth');
        $client->addScope("email");
        $client->addScope("profile");

        return $client;
    }

    public function googleAuth()
    {
        // Intentamos por CodeIgniter, si no, por PHP puro
        $code = $this->request->getVar('code') ?? $_GET['code'] ?? null;
        //var_dump($code);
        if (!$code) {
            // Si sigue vacío, redirigimos a Google
            $client = $this->getClient();
            return redirect()->to($client->createAuthUrl());
        }

        // DEBUG TEMPORAL: Vamos a ver si el código realmente llegó aquí
        // echo "Código recibido: " . $code; die();

        try {
            $client = $this->getClient();
            // IMPORTANTE: Asegúrate de que $code no sea un string vacío aquí
            $token = $client->fetchAccessTokenWithAuthCode($code);
            //var_dump($token); exit();
            if (isset($token['error'])) {
                return redirect()->to(base_url('login'))->with('error', 'Token inválido');
            }

            $client->setAccessToken($token);
            $googleService = new \Google\Service\Oauth2($client);
            $googleUser = $googleService->userinfo->get();

            $userModel = new UserModel();
            $user = $userModel->where('email', $googleUser->email)->first();

            if (!$user) {
                // Registro en PostgreSQL
                $newData = [
                    'username'       => explode('@', $googleUser->email)[0],
                    'password'   => password_hash("bin2hex(random_bytes(10))", PASSWORD_DEFAULT),
                    'name'       => $googleUser->givenName,
                    'lastName'   => $googleUser->familyName ?? 'S/A',
                    'email'      => $googleUser->email,
                    'phone'      => 0,
                    'created_user' => 'SYSTEM_GOOGLE',
                    'photo_url'  => $googleUser->picture,
                    'status'     => 1
                ];

                $userModel->insert($newData);
                $user = $userModel->where('email', $googleUser->email)->first();
            }
            // Guardar datos en sesión
            session()->set([
                'userId' => $user['user_id'],
                'name' => $user['name'],
                'lastName' => $user['lastname'],
                'email' => $user['email'],
                'user' => $user['username'],
                'phone' => $user['phone'],
                'menuItems' => $this->menuItems,
                'loggedIn' => true,
                'isLoggedIn' => true
            ]);

            return redirect()->to(base_url('home'));
        } catch (\Exception $e) {
            // Si el código es inválido, Google lanzará el error aquí
            return "Error al validar con Google: " . $e->getMessage();
        }
    }
}
