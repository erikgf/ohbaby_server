<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpleadoContrato extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        "id_empleado", "fecha_inicio", "fecha_fin", "salario","horas_semana","costo_hora", "costo_dia", "dias_trabajo", "horas_dia"
    ];

    protected $casts = [
        'salario' => 'float',
        "horas_semana"=> 'float',
        "costo_hora"=>'float',
        "costo_dia"=>'float',
        "horas_dia"=>'float'
    ];

    /**
     * Get the empleado associated with the EmpleadoContrato
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class, 'id', 'id_empleado');
    }

    /**
     * The horarios that belong to the EmpleadoContrato
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function horarios(): BelongsToMany
    {
        return $this->belongsToMany(Horario::class, 'empleado_contrato_horario', 'id_empleado_contrato', 'id_horario');
    }

    public function entregas(): HasMany {
        return $this->hasMany(Entrega::class, "id_empleado_contrato", "id");
    }

    public function asistencias(): HasMany {
        return $this->hasMany(AsistenciaRegistroEmpleado::class, "id_empleado_contrato", "id");
    }

}
