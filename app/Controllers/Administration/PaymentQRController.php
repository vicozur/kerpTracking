<?php

namespace App\Controllers\Administration;
use App\Controllers\BaseController;

use App\Libraries\KerpClient;
use Config\Services;

class PaymentQRController extends BaseController
{
    public function index()
    {   
        /*
        $kerp = new KerpClient();
        $resultado = $kerp->listarUsuarios();
        print_r($resultado);
        */
        $this->getBNBToken();
        //var_dump($res);
        $data = [
            'title' => "Pago QR",
            'titleMod' => "Generar Pagos QR"
        ];
        return view('administration/paymentQR', $data);
    }

    public function getBNBToken()
    {
        // 1. Limpiamos cualquier impresión basura previa
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->request('POST', 'http://test.bnb.com.bo/ClientAuthentication.API/api/v1/auth/token', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                    'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                ],
                'json' => [
                    'username' => 's9CG8FE7Id75ef2jeX9bUA==',
                    'password' => '713K7PvTlACs1gdmv9jGgA=='
                ],
                'http_errors' => false
            ]);

            $rawBody = $response->getBody();

            // 2. Si el banco bloqueó la IP
            if (strpos($rawBody, '<html') !== false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'IP bloqueada por el banco (WAF)',
                    'status'  => 403
                ]);
                die(); // Esto evita que se carguen los layouts y views de abajo
            }

            // 3. Respuesta normal
            echo $rawBody;
            die();
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            die();
        }
    }
}
