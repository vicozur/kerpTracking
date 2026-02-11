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

    public function getListadoServerSide($limit, $start, $search, $order, $dir)
    {
        $builder = $this->builder();
        $builder->select('tracking.tramite.*, tt.nombre_tramite as nombre_tipo, d.id as id_documento');
        $builder->join('tracking.tipo_tramite tt', 'tt.id_tipo_tramite = tracking.tramite.id_tipo_tramite', 'left');
        $builder->join('tracking.document d', 'd.id_tramite = tracking.tramite.id_tramite', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('LOWER(tracking.tramite.nombre_completo)', strtolower($search))
                ->orLike('LOWER(tracking.tramite.cite_tramite)', strtolower($search))
                ->orLike('LOWER(tt.nombre_tramite)', strtolower($search))
                ->orLike('LOWER(tracking.tramite.estado_reg)', strtolower($search))
                ->groupEnd();
        }

        // 2. Orden dinámico
        $builder->orderBy($order, $dir);

        // 3. Paginación
        $builder->limit($limit, $start);

        return $builder->get()->getResultArray();
    }

    // Método auxiliar para contar el total de registros filtrados
    public function countFiltrados($search)
    {
        $builder = $this->builder();
        $builder->join('tracking.tipo_tramite tt', 'tt.id_tipo_tramite = tracking.tramite.id_tipo_tramite', 'left');

        if (!empty($search)) {
            $builder->like('tracking.tramite.nombre_completo', $search)
                ->orLike('tracking.tramite.cite_tramite', $search);
        }

        return $builder->countAllResults();
    }
}