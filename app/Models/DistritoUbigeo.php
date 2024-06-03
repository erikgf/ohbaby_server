<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistritoUbigeo extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the provincia that owns the ProvinciaUbigeo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provincia(): BelongsTo
    {
        return $this->belongsTo(ProvinciaUbigeo::class, 'id_provincia', 'id');
    }

}
