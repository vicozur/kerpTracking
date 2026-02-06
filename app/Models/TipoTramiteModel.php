<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoTramiteModel extends Model
{
    protected $table = 'tracking.tipo_tramite';   // ahora apunta a schema + tabla
    protected $primaryKey = 'id_tipo_tramite';       // en PostgreSQL todo quedó en minúscula
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; // también podrías usar 'object'
    protected $useSoftDeletes = false;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    
    // Campos permitidos en inserts/updates
    protected $allowedFields = [
        'nombre_tramite',
        'codigo_tramite',
        'descripcion'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Si quieres timestamps automáticos descomenta y ajusta
    /*
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'createdate';
    protected $updatedField  = 'createdate';
    */

    public function getListadoTramites()
    {
        // Al ser PostgreSQL, el ordenamiento es sensible a mayúsculas/minúsculas
        return $this->orderBy('nombre_tramite', 'ASC')
            ->findAll();
    }

    /**
     * Obtener un trámite específico por su código único
     */
    public function getByCodigo($codigo)
    {
        return $this->where('codigo_tramite', $codigo)->first();
    }
}
