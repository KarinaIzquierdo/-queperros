<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dueno extends Model
{
    protected $table = 'duenos';

    protected $primaryKey = 'id_dueno';

    public $incrementing = true;

    protected $keyType = 'int';
}
