<?php

namespace App\Controllers\Administration;
use App\Controllers\BaseController;
use App\Libraries\KerpClient;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;

class TramitController extends BaseController
{
    protected $tramiteModel, $tipoTramiteModel;
    protected $session;

    public function __construct()
    {
        $this->tramiteModel = new TramiteModel();
        $this->tipoTramiteModel = new TipoTramiteModel();
        $this->session = session();
        /*
        $this->directoryModel = new DirectoryModel();
        $this->countryModel = new CountryModel();
        $this->cityModel = new CityModel();
        $this->categoryModel = new CategoryModel();
        
        */
    }

    public function index()
    {
        /*
        $kerp = new KerpClient();
        $resultado = $kerp->listarMisTramites();
        var_dump(json_encode($resultado));
        */
        $todos = $this->tramiteModel->getListadoTramites();
        // 1. Inicializamos el array de estadísticas
        $stats = [
            'TOTAL'      => count($todos),
            'PENDIENTE'  => 0,
            'EN CURSO'   => 0,
            'APROBADO'   => 0,
            'FINALIZADO' => 0,
            'OBSERVADOS' => 0
        ];

        // 2. Llenamos los contadores recorriendo los trámites
        foreach ($todos as $t) {
            $estado = strtoupper($t['estado_tramite']); // Normalizamos a mayúsculas
            if (array_key_exists($estado, $stats)) {
                $stats[$estado]++;
            }
            if (!empty($t['observacion'])) {
                $stats['OBSERVADOS']++;
            }
        }
        $data = [
            'title' => "Tramites",
            'titleMod' => "Aministrar de tramites",
            'tramites' => $todos,
            'stats'    => $stats, // <--- Aquí es donde se define para la vista
            'tipos'    => $this->tipoTramiteModel->getListadoTramites()
        ];



        return view('administration/tramit', $data);
    }

    public function directorio()
    {
        $todos = $this->tramiteModel->getListadoCompleto(session('user'));

        // Cálculo de estadísticas para los Widgets
        $stats = [
            'TOTAL'      => count($todos),
            'PENDIENTE'  => 0, 'EN CURSO' => 0, 
            'APROBADO'   => 0, 'FINALIZADO' => 0,
            'OBSERVADOS' => 0
        ];

        foreach ($todos as $t) {
            if (isset($stats[$t['estado_tramite']])) $stats[$t['estado_tramite']]++;
            if (!empty($t['observacion'])) $stats['OBSERVADOS']++;
        }

        return view('administration/tracking', [
            'tramites' => $todos,
            'stats'    => $stats,
            'tipos'    => $this->tipoTramiteModel->findAll()
        ]);
    }

    public function getTramite($id)
    {
        $data = $this->tramiteModel->find($id);
        return $this->response->setJSON($data ? ['status'=>'success', 'data'=>$data] : ['status'=>'error']);
    }

    public function update_status()
    {
        $id = $this->request->getPost('id_tramite');
        $estado = $this->request->getPost('estado_tramite');
        $observacion = $this->request->getPost('observacion');

        $data = [
            'estado_tramite' => $estado,
            'observacion'    => $observacion,
            // Opcional: registrar quién hizo el cambio
            // 'updated_user' => session()->get('user_id') 
        ];


        if ($this->tramiteModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'El trámite ahora está en estado: ' . $estado
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No se pudo actualizar el estado.'
        ]);
    }
}
