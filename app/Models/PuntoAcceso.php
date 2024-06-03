<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PuntoAcceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["descripcion"];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
