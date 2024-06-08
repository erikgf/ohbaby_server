<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsistenciaRegistroEmpleado extends Model
{
    use HasFactory, SoftDeletes;
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id_empleado_contrato', 'fecha', 'hora_entrada_mañana', 'hora_salida_mañana', 'hora_entrada_tarde', 'hora_salida_tarde', 'numero_dia_semana', 'id_punto_acceso'
    ];


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
        return $this->hasOne(EmpleadoContrato::class, 'id', 'id_empleado_contrato');
    }

}
