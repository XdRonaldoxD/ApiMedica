<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndicacionesDoc extends Model
{
    protected $table = 'indicacion_docs';
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'hclinip',
        'usuario_id',
        'medicamento',
        'cantidad',
        'formingerir',
        'dias'
    ];

  
}
