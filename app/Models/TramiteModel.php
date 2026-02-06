<?php

namespace App\Models;

use CodeIgniter\Model;

class TramiteModel extends Model
{
    protected $table      = 'tracking.tramite';
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
    /*
            "id_tramite" SERIAL NOT NULL,
            "cite_tramite" VARCHAR(100) NULL DEFAULT NULL,
            "nombre_tramite" VARCHAR(255) NULL DEFAULT NULL,
            "estado_tramite" VARCHAR(50) NULL DEFAULT NULL,
            "estado_reg" VARCHAR(10) NULL DEFAULT NULL,
            "observacion" VARCHAR(300) NULL DEFAULT NULL,
            "num_resolucion" INTEGER NULL DEFAULT NULL,
            "nombre_completo" TEXT NULL DEFAULT NULL,
            "tipo_persona" VARCHAR(50) NULL DEFAULT NULL,
            "created_user" VARCHAR(150) NULL DEFAULT NULL,
            "created_at" TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            "id_tipo_tramite" INTEGER NULL DEFAULT NULL,
            */

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

}
