<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['descripcion', 'total_horas_semana'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get all of the horarioDetalles for the Horario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function horarioDetalles(): HasMany
    {
        return $this->hasMany(HorarioDetalle::class, 'id_horario', 'id');
    }

    /**
     * The empleadoContratos that belong to the Horario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function empleadoContratos(): BelongsToMany
    {
        return $this->belongsToMany(EmpleadoContrato::class, 'empleado_contrato_horario', 'id_horario','id_empleado_contrato');
    }
}
