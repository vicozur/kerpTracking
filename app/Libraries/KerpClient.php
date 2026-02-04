<?php

namespace App\Libraries;

class KerpClient
{
    private $user = "admin";
    private $passFlat = "admin"; // Tu clave real o MD5
    private $baseUrl = "http://172.31.66.185/kerp/pxp/lib/rest/index.php/"; // Dominio de conexion

    /**
     * Funcion de llamada de listado de Usuarios de plataforma kerp
    */
    public function listarUsuarios()
    {
        $cookieFile = WRITEPATH . 'cookie_kerp.txt';

        // 1. Generar Token (Usando la lógica OpenSSL que funcionó)
        $tokenCifrado = $this->encryptPxp("admin", $this->passFlat);
        // 2. Llamada al endpoint
        // Nota: El index.php hará: include_once ... /sis_seguridad/control/ACTUsuario.php
        $res = $this->ejecutar('seguridad/Usuario/listarUsuario', [
            'start' => 0,
            'limit' => 50,
            'sort' => 'id_usuario',
            'dir' => 'ASC',
            'filter' => '' // Pxp suele pedir este parámetro aunque esté vacío
        ], $tokenCifrado, $cookieFile);

        $data = json_decode($res, true);

        if (isset($data['ROOT'])) {
            // Si llegamos aquí y hay datos, ¡el puente está totalmente operativo!
            return $data['datos'];
        }

        return $data;
    }

    /**
     * Funcion de llamada de listado de Usuarios de plataforma kerp
     */
    public function listarMisTramites()
    {
        $cookieFile = WRITEPATH . 'cookie_kerp.txt';
        $tokenCifrado = $this->encryptPxp($this->user, $this->passFlat);

        $params = [
            'start' => 0,
            'limit' => 50,
            'sort'  => 'id_tramite',
            'dir'   => 'ASC'
        ];

        // Prueba con 'tramites' en plural si la carpeta es sis_tramites
        // O prueba con 'tramite' en singular si la carpeta es sis_tramite
        $res = $this->ejecutar('tramites/Tramite/listarTramite', $params, $tokenCifrado, $cookieFile);

        return json_decode($res, true);
    }

    /**
     * Ejecucion de invocacion y llamado de metodo de consulta
    */
    private function ejecutar($endpoint, $params, $token, $cookieFile)
    {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Pxp-User: {$this->user}",
            "Php-Auth-User: {$token}",
            "auth-version: 2", // <--- ESTO ES CRÍTICO: Activa el modo OpenSSL en el servidor
            "Accept: application/json"
        ]);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private function encryptPxp($sValue, $sSecretKey)
    {
        $method = 'AES-256-CBC';
        $salt = openssl_random_pseudo_bytes(8);
        $iv = openssl_random_pseudo_bytes(16);
        $iterations = 999;

        // El servidor hace: hash_pbkdf2('sha512', $password, $salt, $iterations, 64)
        $key = hash_pbkdf2('sha512', $sSecretKey, $salt, $iterations, 64, true);

        $encrypted = openssl_encrypt($sValue, $method, $key, OPENSSL_RAW_DATA, $iv);

        // El servidor espera un JSON base64 en Php-Auth-User
        $data = [
            "ciphertext" => base64_encode($encrypted),
            "iv" => bin2hex($iv),
            "salt" => bin2hex($salt),
            "iterations" => $iterations
        ];

        return base64_encode(json_encode($data));
    }
}
