<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    protected $table = 'historiaclinica';
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'nCitamed',
        'user_id',
        'img_perfil',
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
        'vigencia_paciente',
        'fecha_creacion'
    ];

    public function IndicacionDoc()
    {
        return $this->hasMany(IndicacionesDoc::class, 'hclinip');
    }
}
