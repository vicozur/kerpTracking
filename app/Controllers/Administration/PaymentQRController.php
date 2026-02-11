<?php

namespace App\Controllers\Administration;
use App\Controllers\BaseController;

use App\Libraries\KerpClient;

class PaymentQRController extends BaseController
{
    public function index()
    {   
        /*
        $kerp = new KerpClient();
        $resultado = $kerp->listarUsuarios();
        print_r($resultado);
        */
        
        $data = [
            'title' => "Pago QR",
            'titleMod' => "Generar Pagos QR"
        ];
        return view('administration/paymentQR', $data);
    }
}
