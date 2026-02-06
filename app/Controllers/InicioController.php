<?php

namespace App\Controllers;

use App\Libraries\KerpClient;

class InicioController extends BaseController
{
    public function index()
    {   
        /*
        $kerp = new KerpClient();
        $resultado = $kerp->listarUsuarios();
        print_r($resultado);
        */
        
        $data = [
            'title' => "Inicio",
            'titleMod' => ""
        ];
        return view('home', $data);
    }
}
