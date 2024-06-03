<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProvinciaUbigeo extends Model
{
    use HasFactory;
    public $incrementing = false;
    /**
     * Get the departamento that owns the ProvinciaUbigeo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(DepartamentoUbigeo::class, 'id_departamento', 'id');
    }

    /**
     * Get all of the distritos for the ProvinciaUbigeo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function distritos(): HasMany
    {
        return $this->hasMany(DistritoUbigeo::class, 'id_provincia', 'id');
    }
}
