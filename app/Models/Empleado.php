<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id_tipo_documento', 'numero_documento', 'apellido_paterno','apellido_materno','nombres','codigo_unico','direccion','distrito_ubigeo', 'pais', 'fecha_nacimiento'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get all of the contratos for the Empleado
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contratos(): HasMany
    {
        return $this->hasMany(EmpleadoContrato::class, 'id_empleado', 'id');
    }

    public function contratoActivo(): HasOne
    {
        return $this->hasOne(EmpleadoContrato::class, 'id_empleado', 'id')->whereNull("fecha_fin");
    }
}
