<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrega extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_tipo_entrega","id_empleado_contrato","fecha_registro","monto_entregado", "motivo"
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
    public function tipoEntrega(): HasOne
    {
        return $this->hasOne(TipoEntrega::class, "id", "id_tipo_entrega");
    }

    /**
     * Get the empleadoContrato associated with the Entrega
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleadoContrato(): HasOne
    {
        return $this->hasOne(EmpleadoContrato::class, 'id', 'id_empleado_contrato');
    }

    /**
     * Get all of the cuotas for the Entrega
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuotas(): HasMany
    {
        return $this->hasMany(EntregaCuota::class, 'id_entrega', 'id');
    }
}
