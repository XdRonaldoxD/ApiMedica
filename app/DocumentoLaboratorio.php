<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoLaboratorio extends Model
{
    protected $table = 'doc_historia_clinia_p_s';
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'usuario_id',
        'hclinip_id',
        'documento',
        'fecha_documento'
    ];
}
