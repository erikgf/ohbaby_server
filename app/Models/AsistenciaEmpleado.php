<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsistenciaEmpleado extends Model
{
    use HasFactory, SoftDeletes;
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id_empleado_contrato', 'fecha', 'hora_entrada', 'fecha_hora_entrada', 'hora_salida', 'fecha_hora_salida', 'numero_dia_semana', 'id_punto_acceso',"total_horas"
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

    public function empleadoBase() {
        return $this->hasOneThrough(Empleado::class, EmpleadoContrato::class,
                            "id", "id",
                            "id_empleado_contrato", "id_empleado");
    }
}
