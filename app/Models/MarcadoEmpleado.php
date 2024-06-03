<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarcadoEmpleado extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_empleado_contrato', 'hora', 'fecha', 'numero_dia_semana', 'id_punto_acceso'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * Get the puntoAcceso associated with the MarcadoEmpleado
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function puntoAcceso(): HasOne
    {
        return $this->hasOne(PuntoAcceso::class, 'id', 'id_punto_acceso');
    }

    /**
     * Get the empleado associated with the MarcadoEmpleado
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class, 'id', 'id_empleado');
    }
}
