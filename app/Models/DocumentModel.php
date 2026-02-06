<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table      = 'tracking.document';
    protected $primaryKey = 'id';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $allowedFields = [
        'id_tramite',
        'doc_ci',
        'doc_memorial',
        'doc_folio',
        'doc_plano',
        'doc_poder',
        'doc_ci_tramitador',
        'created_user'
    ];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

}
