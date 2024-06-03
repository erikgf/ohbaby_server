<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepartamentoUbigeo extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get all of the provincias for the DepartamentoUbigeo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function provincias(): HasMany
    {
        return $this->hasMany(ProvinciaUbigeo::class, 'id_departamento_ubigeo', 'id');
    }
}
