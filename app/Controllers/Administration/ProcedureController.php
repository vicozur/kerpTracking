<?php

namespace App\Controllers\Administration;

use App\Controllers\BaseController;
use App\Libraries\KerpClient;
use App\Models\DocumentModel;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;

class ProcedureController extends BaseController
{
    protected $tipoTramiteModel, $tramiteModel, $documentModel;
    protected $session;

    public function __construct()
    {
        $this->tipoTramiteModel = new TipoTramiteModel();
        $this->documentModel = new DocumentModel();
        $this->tramiteModel = new TramiteModel();
    }

    public function index()
    {
        /*
        $kerp = new KerpClient();
        $resultado = $kerp->listarMisTramites();
        var_dump(json_encode($resultado));
        */
        // Obtiene el primer registro basado en la llave primaria (id_tramite ASC)
        

        $data = [
            'title' => "Nuevo tramite",
            'titleMod' => "Registrar nuevo tramite",
            'tipos' => $this->tipoTramiteModel->getListadoTramites()
        ];

        return view('administration/procedure', $data);
    }

    public function create()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        /*
        $db = \Config\Database::connect();
        $db->transStart();
        */
        try {
            // 1. Insertar el Trámite primero para obtener el ID
            $dataTramite = [
                'id_tipo_tramite' => $this->request->getPost('tipo_tramite'),
                'cite_tramite' => null,
                'nombre_tramite' => null,
                'estado_tramite'  => 'PENDIENTE',
                'estado_reg' => null,
                'observacion' => null,
                'num_resolucion' => null,
                'nombre_completo' => null,
                'tipo_persona' => 'PROPIETARIO',
                'created_user' => 'SYSTEM'
            ];
            
            $idTramite = $this->tramiteModel->insert($dataTramite);

            if (!$idTramite) {
                throw new \Exception("Error al crear el trámite.". json_encode($dataTramite));
            }

            $documentData = [
                'id_tramite'   => $idTramite,
                'created_user' => session()->get('user'), // O quien esté logueado
            ];

            $fileMap = [
                'doc_ci'            => 'doc_ci',
                'doc_memorial'      => 'doc_memorial',
                'doc_folio'         => 'doc_folio',
                'doc_plano'         => 'doc_plano',
                'doc_poder'         => 'doc_poder',
                'doc_ci_tramitador' => 'doc_ci_tramitador'
            ];

            foreach ($fileMap as $inputName => $columnName) {
                $file = $this->request->getFile($inputName);

                if ($file && $file->isValid() && !$file->hasMoved()) {
                    // 1. Leemos el contenido del archivo
                    $fp = fopen($file->getTempName(), 'rb');
                    $binaryData = fread($fp, $file->getSize());
                    fclose($fp);

                    // 2. CONVERSIÓN CRUCIAL:
                    // Convertimos a hex y agregamos el prefijo \x que Postgres reconoce como BYTEA
                    $documentData[$columnName] = '\x' . bin2hex($binaryData);
                } else {
                    // Validar obligatorios
                    if (in_array($inputName, ['doc_ci', 'doc_memorial', 'doc_folio', 'doc_plano'])) {
                        return redirect()->back()->with('error', "El archivo $inputName es obligatorio.");
                    }
                    $documentData[$columnName] = null;
                }
            }

            // Al insertar, CodeIgniter cerrará el recurso automáticamente
            //$this->documentModel->insert($documentData);
            
            if (!$this->documentModel->insert($documentData)) {
                throw new \Exception("Error al guardar los archivos binarios.");
            }
            
            // Sustituye el insert del modelo por esto para probar:
            /*
            $builder = $db->table('document');
            if (!$builder->insert($documentData)) {
                $error = $db->error();
                throw new \Exception("Error DB: " . $error['message']);
            }
            */
            //$db->transComplete();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Tr&aacute;mite y documentos guardados en BD.'
            ]);
        } catch (\Exception $e) {
            //$db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
