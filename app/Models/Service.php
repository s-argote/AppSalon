<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio',
        'descripcion',
        'duracion',
        'activo',
    ];

    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'citasServicios', 'servicioId', 'citaId');
    }
}
