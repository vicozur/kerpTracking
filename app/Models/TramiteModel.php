<?php

namespace App\Models;

use CodeIgniter\Model;

class TramiteModel extends Model
{
    protected $table      = 'tracking.tramite'; // Nombre real en tu DB
    protected $primaryKey = 'id_tramite';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $allowedFields = [
        'id_tipo_tramite',
        'cite_tramite',
        'nombre_tramite',
        'estado_tramite',
        'estado_reg',
        'observacion',
        'num_resolucion',
        'nombre_completo',
        'tipo_persona',
        'created_user'
    ];

    protected $returnType = 'array';

    /**
     * Obtiene el listado usando los métodos internos del modelo ($this)
     * He renombrado la función a getListadoCompleto para que coincida 
     * con lo que tu controlador está buscando.
     */
    public function getListadoCompleto($user)
    {
        return $this->select('tracking.tramite.*, tt.nombre_tramite as nombre_tipo, d.id as doc_id')
            ->join('tracking.tipo_tramite tt', 'tt.id_tipo_tramite = tracking.tramite.id_tipo_tramite', 'left')
            ->join('tracking.document d', 'd.id_tramite = tracking.tramite.id_tramite', 'left')
            ->where('tracking.tramite.created_user', $user) 
            ->orderBy('tracking.tramite.created_at', 'DESC')
                ->findAll(); // findAll() ya retorna el result array según $returnType
    }

    public function getListadoTramites()
    {
        return $this->select('tracking.tramite.*, tt.nombre_tramite as nombre_tipo, d.id as id_documento')
            ->join('tracking.tipo_tramite tt', 'tt.id_tipo_tramite = tracking.tramite.id_tipo_tramite', 'left')
            ->join('tracking.document d', 'd.id_tramite = tracking.tramite.id_tramite', 'left')
            ->orderBy('tracking.tramite.created_at', 'DESC')
            ->findAll();
    }
}