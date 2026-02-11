<?php

namespace App\Controllers\Administration;

use App\Controllers\BaseController;
use App\Libraries\KerpClient;
use App\Models\DocumentModel;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;

class TramitController extends BaseController
{
    protected $tramiteModel, $tipoTramiteModel, $documentModel;
    protected $session;

    public function __construct()
    {
        $this->tramiteModel = new TramiteModel();
        $this->tipoTramiteModel = new TipoTramiteModel();
        $this->documentModel = new DocumentModel();
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
            'APROBADO'   => 0,
            'RECHAZADO' => 0
        ];

        // 2. Llenamos los contadores recorriendo los trámites
        foreach ($todos as $t) {
            $estado = strtoupper($t['estado_reg']); // Normalizamos a mayúsculas
            if (array_key_exists($estado, $stats)) {
                $stats[$estado]++;
            }
        }
        $data = [
            'title' => "Tramites",
            'titleMod' => "Aministrar de tramites",
            'stats'    => $stats, // <--- Aquí es donde se define para la vista
            'tipos'    => $this->tipoTramiteModel->getListadoTramites()
        ];



        return view('administration/tramit', $data);
    }

    public function ajaxListado()
    {
        $params = $this->request->getPost();

        // Mapeo exacto según el orden de tus <th> en el HTML
        $columns = [
            0 => 'tt.nombre_tramite', // Trámite
            1 => 'tracking.tramite.estado_tramite', // Situación
            2 => 'tracking.tramite.estado_reg', // Estado
            3 => 'tracking.tramite.nombre_completo', // Solicitante
            4 => 'tracking.tramite.created_at', // Fecha
            5 => 'id_tramite' // Proceso (usualmente ordenamos por ID aquí)
        ];

        $limit = $params['length'];
        $start = $params['start'];
        $search = $params['search']['value'];

        // Validar que el índice del orden exista para evitar errores
        $orderIndex = $params['order'][0]['column'];
        $order = $columns[$orderIndex] ?? 'tracking.tramite.created_at';
        $dir = $params['order'][0]['dir'];

        $data = $this->tramiteModel->getListadoServerSide($limit, $start, $search, $order, $dir);

        // Agregamos el hash CSRF a la respuesta para que la siguiente petición no falle
        return $this->response->setJSON([
            "draw"            => intval($params['draw']),
            "recordsTotal"    => intval($this->tramiteModel->countAll()),
            "recordsFiltered" => intval($this->tramiteModel->countFiltrados($search)),
            "data"            => $data,
            "csrf_hash"       => csrf_hash() // <--- CRÍTICO para no morir en el segundo clic
        ]);
    }

    public function updateTramite()
    {
        $id = $this->request->getPost('id_tramite');
        $nombres = $this->request->getPost('nombre_completo');
        $numResolucion = $this->request->getPost('num_resolucion');
        $nroCite = $this->request->getPost('cite_tramite');
        $observation = $this->request->getPost('observacion');
        $data = [
            'nombre_completo' => (!empty($nombres)) ? $nombres : null,
            'cite_tramite'    => (!empty($nroCite)) ? $nroCite : null,
            'num_resolucion'  => (!empty($numResolucion)) ? $numResolucion : null,
            'observacion'     => (!empty($observation)) ? $observation : null,
            'estado_reg'      => $this->request->getPost('estado_reg')
        ];

        if ($this->tramiteModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Trámite actualizado correctamente']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Error al actualizar']);
    }

    public function descargar($id, $columna)
    {
        //$db = \Config\Database::connect();
        $tramite = $this->documentModel->table('document')->where('id_tramite', $id)->get()->getRowArray();

        if (!$tramite || empty($tramite[$columna])) {
            return "Archivo no encontrado.";
        }

        // 1. Obtener la cadena hexadecimal
        $hexData = $tramite[$columna];

        // 2. Limpiar el prefijo \x si existe (común en PostgreSQL o ciertos dumps)
        if (strpos($hexData, '\x') === 0) {
            $hexData = substr($hexData, 2);
        }

        // 3. CONVERSIÓN CRUCIAL: De Hexadecimal a Binario puro
        $binario = hex2bin($hexData);

        // 4. Limpiar buffers para evitar archivos corruptos
        while (ob_get_level()) {
            ob_end_clean();
        }

        // 5. Enviar cabeceras
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"documento_{$id}.pdf\"");
        header("Content-Length: " . strlen($binario));
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");

        echo $binario;
        exit;
    }
}
