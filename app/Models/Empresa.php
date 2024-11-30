<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "numero_documento","descripcion"
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'id_empresa', 'id');
    }
}
