<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HorarioDetalle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "hora_inicio", "hora_fin", "dias", "id_horario"
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the Horario that owns the HorarioDetalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Horario(): BelongsTo
    {
        return $this->belongsTo(Horario::class, 'id', 'id_horario');
    }
}
