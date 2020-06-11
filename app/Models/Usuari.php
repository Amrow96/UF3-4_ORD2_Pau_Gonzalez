<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuari extends Model
{
    //Definimos la tabla y su primary key, puesto que la base de datos esta definida en el .env
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    public function cursos()
    {
        return $this->hasMany('App\Models\Curso', 'id');
    }
}
