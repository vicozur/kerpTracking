<?php

namespace App\Libraries;

class KerpConnection
{
    // Ruta base apuntando al gateway REST de PXP
    private $baseUrl = 'http://172.31.66.185/kerp/lib/rest/';
    private $user    = 'admin';
    private $pass    = '21232f297a57a5a743894a0e4a801fc3'; // Tu MD5

    public function ejecutarLogin()
    {
        $client = \Config\Services::curlrequest();

        try {
            // PASO 1: Obtener llaves públicas (Ejecuta getPublicKey en ACTAuten.php)
            // Esto es vital porque ACTAuten inicializa la $_SESSION en el servidor de KERP
            $resKeys = $client->request('POST', $this->baseUrl . 'seguridad/Auten/getPublicKey', [
                'form_params' => ['_tipo' => 'restAuten']
            ]);

            // PASO 2: Verificar Credenciales (Ejecuta verificarCredenciales en ACTAuten.php)
            // Es OBLIGATORIO enviar '_tipo' => 'restAuten' para que el método devuelva JSON
            $response = $client->request('POST', $this->baseUrl . 'seguridad/Auten/verificarCredenciales', [
                'form_params' => [
                    'usuario'    => $this->user,
                    'contrasena' => $this->pass,
                    '_tipo'      => 'restAuten' 
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            $resultado = json_decode($response->getBody(), true);

            if (isset($resultado['success']) && $resultado['success'] === true) {
                // Si el login es correcto, el servidor de KERP habrá creado una sesión activa
                return [
                    'status' => 'success',
                    'data'   => $resultado
                ];
            } else {
                return [
                    'status'  => 'error',
                    'message' => $resultado['mensaje'] ?? 'Credenciales incorrectas'
                ];
            }

        } catch (\Exception $e) {
            return [
                'status'  => 'error',
                'message' => 'No se pudo conectar con el servidor KERP: ' . $e->getMessage()
            ];
        }
    }
}