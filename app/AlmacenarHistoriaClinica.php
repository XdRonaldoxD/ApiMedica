<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlmacenarHistoriaClinica extends Model
{
    protected $table = 'almacenar_historia_clincicas';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'usuario_id',
        'hclinip',
        'nCitamed',
        'nombre',
        'apellido',
        'sexo',
        'edad',
        'dni',
        'fecha_nacimiento',
        'direccion',
        'celular',
        'whatsapp',
        'email',
        'facebook',
        'contactoCentroM',
        'motivoCons',
        'GP',
        'FUR',
        'PAP',
        'MAC',
        'RAM',
        'antecedenteP',
        'antecedenteF',
        'pa',
        't',
        'fc',
        'fr',
        'peso',
        'talla',
        'Comentclinico',
        'diagnostico',
        'Tratamiento',
        'DocLaboratorio',
        'imageneologia',
        'pcita',
        "fecha_creacion"
    ];
}
