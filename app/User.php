<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table="users";
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','nombre', 'email', 'password','apellido','dni','direccion','celular','role','vigencia_users','session_id','path_user'
    ];
    protected $hidden = [
        'password', 'remember_token','dni'
    ];
    public $timestamps = false;
}
