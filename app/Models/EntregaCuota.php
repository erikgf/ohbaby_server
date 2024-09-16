<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntregaCuota extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "numero_cuota","id_entrega","fecha_cuota","monto_cuota","es_entregado"
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the user that owns the TipoEntrega
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entrega(): BelongsTo
    {
        return $this->belongsTo(Entrega::class, "id_entrega", "id");
    }
}
