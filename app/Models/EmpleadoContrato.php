<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        "id_empleado", "fecha_inicio", "fecha_fin", "salario","costo_hora", "costo_dia", "dias_trabajo", "horas_dia"
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

}
