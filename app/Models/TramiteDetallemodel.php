<?php

namespace App\Models;

use CodeIgniter\Model;

class TramiteDetalleModel extends Model
{
    protected $table      = 'tracking.tramite_detalle'; // Nombre real en tu DB
    protected $primaryKey = 'id_detalle';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $allowedFields = [
        'id_tramite',
        'descripcion',
        'cargo',
        'email_empresa',
        'estado_reg',
        'estado_tramite',
        'created_user',
        'cite_tramite'
    ];

    protected $returnType = 'array';

    /**
     * Obtiene el historial completo de un trámite específico
     * @param int $id_tramite
     * @return array
     */
    public function getSeguimientoCompleto($id_tramite)
    {
        return $this->where('id_tramite', $id_tramite)
                    ->orderBy('created_at', 'ASC') // Orden ascendente para ver el flujo desde el inicio
                    ->findAll();
    }

}